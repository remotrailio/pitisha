<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_referral_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('reward_type', 50);
            $table->foreignId('reward_ticket_type_id')->nullable()->constrained('ticket_types')->nullOnDelete();
            $table->decimal('reward_value', 10, 2)->nullable();
            $table->enum('status', ['pending', 'earned', 'claimed', 'revoked'])->default('earned');
            $table->dateTime('claimed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_referral_rewards');
    }
};
