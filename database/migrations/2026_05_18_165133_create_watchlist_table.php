<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('watchlist', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lot_id')->constrained('lots')->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['user_id', 'lot_id']);
            $table->index('lot_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('watchlist');
    }
};
