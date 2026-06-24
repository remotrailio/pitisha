<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();

            $table->string('name');
            $table->string('code');

            $table->enum('type', ['percentage', 'fixed']);
            $table->decimal('value', 10, 2);

            $table->dateTime('starts_at')->nullable();
            $table->dateTime('expires_at')->nullable();

            $table->unsignedInteger('max_uses');
            $table->unsignedInteger('used_count')->default(0);

            $table->decimal('minimum_order_amount', 10, 2)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['event_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_codes');
    }
};
