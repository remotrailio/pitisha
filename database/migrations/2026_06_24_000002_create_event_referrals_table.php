<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('referrer_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('referred_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('referred_order_id')->constrained('orders')->cascadeOnDelete();
            $table->enum('status', ['pending', 'qualified', 'cancelled'])->default('qualified');
            $table->timestamps();

            $table->unique(['event_id', 'referred_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_referrals');
    }
};
