<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Form එකෙන් එන data කෙලින්ම save කරන්න අවසර දෙන columns ටික
    protected $fillable = [
        'player_id', 
        'box_number', 
        'quantity', 
        'total_amount', 
        'payment_status', 
        'game_date'
    ];

    // Relationship එක: මේ Transaction එක අයිති Player
    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}