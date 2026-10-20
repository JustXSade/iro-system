<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Player;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Helper method to enforce the 8 PM reset rule
    private function getGameDate()
    {
        $now = Carbon::now();
        return $now->hour >= 20 ? $now->toDateString() : $now->subDay()->toDateString();
    }

    public function index()
    {
        $gameDate = $this->getGameDate();

        // Fetch raw transactions for the day
        $rawTransactions = Transaction::with('player')
            ->where('game_date', $gameDate)
            ->latest()
            ->get();

        // Group transactions by Player AND by the exact Minute they were created
        // This combines multiple boxes bought at the same time into one row
        $transactions = $rawTransactions->groupBy(function ($item) {
            return $item->player_id . '-' . $item->created_at->format('Y-m-d H:i');
        })->map(function ($group) {
            // Combine all box details for this grouped transaction
            $boxes = [];
            foreach ($group as $item) {
                $boxes[] = $item->box_number . ' (x' . $item->quantity . ')';
            }

            return (object) [
                'created_at' => $group->first()->created_at,
                'player' => $group->first()->player,
                'combined_boxes' => implode(', ', $boxes), // e.g., "15 (x4), 20 (x3)"
                'total_amount' => $group->sum('total_amount'),
            ];
        })->values(); // Reset array keys

        // Summary calculations
        $totalRevenue = $rawTransactions->sum('total_amount');
        $cashCollected = Payment::whereDate('created_at', \Carbon\Carbon::today())->sum('amount');
        $totalNaya = Player::where('balance', '<', 0)->sum('balance') * -1;
        $totalBoxes = $rawTransactions->sum('quantity');

        // Live Board Stats
        $boxStats = Transaction::where('game_date', $gameDate)
            ->selectRaw('box_number, SUM(quantity) as total_sold')
            ->groupBy('box_number')
            ->pluck('total_sold', 'box_number')
            ->toArray();

        return view('dashboard', compact(
            'transactions',
            'totalRevenue',
            'cashCollected',
            'totalNaya',
            'totalBoxes',
            'boxStats'
        ));
    }

    public function store(Request $request)
    {
        // 1. Data Validation
        $request->validate([
            'player_name' => 'required|string|max:255',
            'box_number' => 'required|array',
            'box_number.*' => 'required|integer|min:1|max:100',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
            'cash_paid' => 'required|numeric|min:0',
        ]);

        $gameDate = $this->getGameDate();
        $unitPrice = 100; // Box එකක මිල මෙතැනින් වෙනස් කරන්න

        DB::beginTransaction();

        try {
            // Find existing Player or Create a new one
            $player = Player::firstOrCreate(
                ['name' => $request->player_name],
                ['balance' => 0]
            );

            $totalGameCost = 0;

            // Process each Box Transaction
            foreach ($request->box_number as $index => $boxNo) {
                $qty = $request->quantity[$index];
                $cost = $qty * $unitPrice;
                $totalGameCost += $cost;

                // Check availability (Max 20 sheets per box limit)
                $soldQty = Transaction::where('game_date', $gameDate)
                    ->where('box_number', $boxNo)
                    ->lockForUpdate()
                    ->sum('quantity');

                $available = 20 - $soldQty;

                if ($qty > $available) {
                    throw new \Exception("Box No {$boxNo} only has {$available} sheets available.");
                }

                // Insert Transaction
                Transaction::create([
                    'game_date' => $gameDate,
                    'player_id' => $player->id,
                    'box_number' => $boxNo,
                    'quantity' => $qty,
                    'total_amount' => $cost,
                ]);
            }

            // NEW: Prevent Advance Payments and Human Typing Errors
            // Calculate total past debt (convert negative to positive for calculation)
            $pastDebt = $player->balance < 0 ? abs($player->balance) : 0;
            $maximumPayable = $totalGameCost + $pastDebt;

            // Block if cash paid is greater than total owed
            if ($request->cash_paid > $maximumPayable) {
                throw new \Exception("Overpayment restricted. Total Due is Rs. {$maximumPayable} (Bill: Rs. {$totalGameCost} + Debt: Rs. {$pastDebt}). Advance payments are not allowed.");
            }

            // Save Payment if cash was provided
            if ($request->cash_paid > 0) {
                Payment::create([
                    'player_id' => $player->id,
                    'amount' => $request->cash_paid,
                    'type' => 'Game Payment'
                ]);
            }

            // Update Player Wallet Balance
            $player->balance = $player->balance - $totalGameCost + $request->cash_paid;
            $player->save();

            DB::commit();
            return back()->with('success', 'Entry & Payment Saved Successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // Search Players for Autocomplete
    public function searchPlayers(Request $request)
    {
        $query = $request->get('q');

        if (!$query) {
            return response()->json([]);
        }

        // Search by name (case-insensitive) and limit to 10 results
        $players = Player::where('name', 'LIKE', '%' . $query . '%')
            ->limit(10)
            ->get(['id', 'name', 'balance']);

        return response()->json($players);
    }

    public function directoryIndex(Request $request)
    {
        $search = $request->query('search');
        
        $players = Player::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', '%' . $search . '%');
        })->orderBy('name')->get();

        return view('directory', compact('players', 'search'));
    }

    public function playerDetails($id)
    {
        $player = Player::findOrFail($id);
        
        return response()->json([
            'id' => $player->id,
            'name' => $player->name,
            'balance' => $player->balance
        ]);
    }

    public function settleDebt(Request $request)
    {
        $request->validate([
            'player_id' => 'required|exists:players,id',
            'amount' => 'required|numeric|min:1'
        ]);

        DB::beginTransaction();

        try {
            $player = Player::lockForUpdate()->findOrFail($request->player_id);
            
            $dueAmount = $player->balance < 0 ? abs($player->balance) : 0;

            if ($dueAmount == 0) {
                throw new \Exception("This player has no pending debts.");
            }

            if ($request->amount > $dueAmount) {
                throw new \Exception("Cannot pay more than the total due (Rs. {$dueAmount}).");
            }

            Payment::create([
                'player_id' => $player->id,
                'amount' => $request->amount,
                'type' => 'Debt Settlement'
            ]);

            $player->balance = $player->balance + $request->amount;
            $player->save();

            DB::commit();
            return back()->with('success', 'Debt settled successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
