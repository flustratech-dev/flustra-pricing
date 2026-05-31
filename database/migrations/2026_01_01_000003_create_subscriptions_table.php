<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained();
            $table->enum('billing_cycle', ['monthly', 'yearly']);
            $table->enum('status', ['active', 'cancelled', 'expired', 'pending'])->default('pending');
            $table->decimal('price', 10, 2);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->boolean('auto_renew')->default(true);
            $table->unsignedBigInteger('payment_method_id')->nullable(); // custom nullable reference without constraints to avoid missing payment_methods table
            $table->string('external_subscription_id')->nullable()->unique();
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
            $table->index(['ended_at', 'auto_renew']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
