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

            // Foreign Key එක (Players table එකට link කරනවා)
            // onDelete('cascade') දැම්මම, player ව delete කරොත් එයාගේ transactions ඔක්කොම auto delete වෙනවා.
            $table->foreignId('player_id')->constrained('players')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('box_number'); // කොටු අංකය (1 - 100)
            $table->integer('quantity'); // කොළ කීයක් ගත්තද
            $table->decimal('total_amount', 10, 2); // ගෙවන්න ඕන මුළු ගාණ
            $table->string('payment_status')->default('Pending'); // Paid, Pending, Not Paid
            $table->date('game_date'); // රෑ 8ට මාරු වෙන දවස track කරන්න

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
