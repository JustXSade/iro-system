<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Determine the current game date based on the 8 PM reset rule
        $now = Carbon::now();
        
        if ($now->hour >= 20) {
            $gameDate = $now->toDateString();
        } else {
            $gameDate = $now->subDay()->toDateString();
        }

        // Fetch all transactions for the calculated game date
        $transactions = Transaction::with('player')
            ->where('game_date', $gameDate)
            ->get();

        // Calculate summary statistics
        $totalRevenue = $transactions->sum('total_amount');
        $cashCollected = $transactions->where('payment_status', 'Paid')->sum('total_amount');
        $totalNaya = $transactions->where('payment_status', 'Pending')->sum('total_amount');
        $totalBoxes = $transactions->sum('quantity');

        // Pass data to the view
        return view('dashboard', compact(
            'transactions',
            'totalRevenue',
            'cashCollected',
            'totalNaya',
            'totalBoxes',
            'gameDate'
        ));
    }
}