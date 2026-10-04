<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
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

        // 1. Fetch Summary Data
        $transactions = Transaction::where('game_date', $gameDate)->get();
        $totalRevenue = $transactions->sum('total_amount');
        $cashCollected = $transactions->where('payment_status', 'Paid')->sum('total_amount');
        $totalNaya = $transactions->whereIn('payment_status', ['Pending', 'Not Paid'])->sum('total_amount');
        $totalBoxes = $transactions->sum('quantity');

        // 2. Fetch Live Board Status (Grouped by box number)
        $boxStats = Transaction::where('game_date', $gameDate)
            ->selectRaw('box_number, SUM(quantity) as total_sold')
            ->groupBy('box_number')
            ->pluck('total_sold', 'box_number')
            ->toArray();

        return view('dashboard', compact(
            'transactions', 'totalRevenue', 'cashCollected', 'totalNaya', 'totalBoxes', 'boxStats'
        ));
    }

    public function store(Request $request)
    {
        // 1. Precise Validation for dynamic arrays
        $request->validate([
            'player_name' => 'required|string|max:255',
            'box_number' => 'required|array',
            'box_number.*' => 'required|integer|min:1|max:100',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1|max:20',
            'payment_status' => 'required|in:Paid,Pending,Not Paid',
        ]);

        $gameDate = $this->getGameDate();
        $unitPrice = 1000; // NOTE: Adjust your box price here

        // 2. Database Transaction for Security (Prevents overselling)
        DB::beginTransaction();

        try {
            foreach ($request->box_number as $index => $boxNo) {
                $qty = $request->quantity[$index];

                // Check remaining availability to prevent exceeding 20
                $soldQty = Transaction::where('game_date', $gameDate)
                                      ->where('box_number', $boxNo)
                                      ->lockForUpdate() // Locks the row until transaction completes
                                      ->sum('quantity');
                
                $available = 20 - $soldQty;

                if ($qty > $available) {
                    throw new \Exception("Box number {$boxNo} only has {$available} left.");
                }

                // Insert record
                Transaction::create([
                    'game_date' => $gameDate,
                    'player_name' => $request->player_name,
                    'box_number' => $boxNo,
                    'quantity' => $qty,
                    'total_amount' => $qty * $unitPrice,
                    'payment_status' => $request->payment_status,
                ]);
            }

            DB::commit();
            return back()->with('success', 'Entry added successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}