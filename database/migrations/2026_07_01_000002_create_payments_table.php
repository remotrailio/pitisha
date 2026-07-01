<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('shortcode', 20)->nullable();

            $table->enum('status', ['processing', 'paid', 'failed', 'unknown', 'refunded'])->default('processing');
            $table->string('provider', 50)->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('kes');
            $table->string('method', 50)->nullable();

            $table->string('reference')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('checkout_request_id')->nullable()->index();
            $table->string('merchant_request_id')->nullable();
            $table->string('receipt_number')->nullable();
            $table->json('response')->nullable();
            $table->string('failure_reason', 500)->nullable();

            $table->tinyInteger('status_query_attempts')->unsigned()->default(0);
            $table->dateTime('last_status_query_at')->nullable();
            $table->dateTime('callback_received_at')->nullable();
            $table->dateTime('initiated_at')->nullable();
            $table->dateTime('completed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
