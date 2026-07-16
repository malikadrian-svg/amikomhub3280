<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Transactions represents a single payment attempt for an order.
     * One order can have multiple transaction records (e.g., retry after failure).
     *
     * Key design: `gateway_order_id` is what we send to Midtrans as their order_id.
     * This is different from our internal `orders.order_number`.
     * The `raw_response` JSON stores the full Midtrans notification payload for
     * debugging and compliance without needing to call their API again.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->restrictOnDelete();

            // The ID sent to Midtrans as their "order_id" (was `order_id` before)
            $table->string('gateway_order_id', 100)->unique();

            // Midtrans transaction ID from their response
            $table->string('gateway_transaction_id', 100)->nullable();

            $table->string('payment_gateway', 50)->default('midtrans');
            $table->string('payment_type', 50)->nullable();   // "gopay", "bca_va", etc.
            $table->string('snap_token')->nullable();

            $table->unsignedInteger('amount');

            $table->string('status', 50)->default('pending');

            // Full Midtrans notification payload (JSON) for audit trail
            $table->json('raw_response')->nullable();

            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index('order_id', 'idx_transactions_order');
            $table->index('gateway_order_id', 'idx_transactions_gateway_order');
            $table->index('status', 'idx_transactions_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
