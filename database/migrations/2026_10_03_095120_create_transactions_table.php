<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('game_date')->index(); // Indexed for fast daily querying
            $table->string('player_name');
            $table->integer('box_number');
            $table->integer('quantity');
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_status', ['Paid', 'Pending', 'Not Paid']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
