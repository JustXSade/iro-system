<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    // Database එකට save කරන්න අවසර දෙන columns
    protected $fillable = ['name'];

    // Relationship එක: Player කෙනෙක්ට Transactions කිහිපයක් තියෙනවා
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}