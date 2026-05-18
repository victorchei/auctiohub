<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lot_id')->constrained('lots')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->timestamp('placed_at')->useCurrent();
            $table->timestamps();

            $table->unique(['lot_id', 'amount']);
            $table->index(['lot_id', 'placed_at']);
            $table->index(['user_id', 'placed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};
