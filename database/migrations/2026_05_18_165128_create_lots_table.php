<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->foreignId('winner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('starting_price', 12, 2);
            $table->decimal('current_price', 12, 2);
            $table->decimal('bid_increment', 12, 2)->default(1.00);
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->enum('status', ['draft', 'active', 'ended', 'cancelled'])->default('draft')->index();
            $table->string('cover_image_path')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'ends_at']);
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lots');
    }
};
