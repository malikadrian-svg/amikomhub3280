<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Orders represent the customer's purchase intent.
     * Transactions (separate table) represent payment attempts.
     * Separating them allows: multiple payment retries per order, refunds,
     * and a clean audit trail without corrupting the order record.
     *
     * Customer snapshot columns (name, email, phone) capture the data at
     * purchase time — not linked by FK so the order survives profile changes.
     *
     * organization_id and event_id are denormalized from order_items for
     * dashboard performance (avoid JOINs on hot paths).
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Tenant key — all organizer queries scope by this
            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->restrictOnDelete();

            // The authenticated buyer
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            // Denormalized for dashboard "orders for event X" queries
            $table->foreignId('event_id')
                ->constrained('events')
                ->restrictOnDelete();

            // Human-readable order reference shown to customer
            $table->string('order_number', 50)->unique();

            // Snapshot at purchase time — survives user profile edits
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone', 20);

            $table->unsignedInteger('subtotal');      // Sum of items before fees
            $table->unsignedInteger('platform_fee')->default(0);
            $table->unsignedInteger('total_amount');  // What Midtrans charges

            $table->enum('status', [
                'pending',
                'paid',
                'completed',
                'cancelled',
                'refunded',
                'expired',
            ])->default('pending');

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('organization_id', 'idx_orders_org');
            $table->index('user_id', 'idx_orders_user');
            $table->index('event_id', 'idx_orders_event');
            $table->index('status', 'idx_orders_status');
            $table->index('created_at', 'idx_orders_created');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
