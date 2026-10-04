<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_date',
        'player_name',
        'box_number',
        'quantity',
        'total_amount',
        'payment_status'
    ];
}
