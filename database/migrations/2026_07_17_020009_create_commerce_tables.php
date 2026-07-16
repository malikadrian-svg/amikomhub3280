<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Line items within an order
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            // RESTRICT: prevent deleting a ticket type that has been sold
            $table->foreignId('ticket_type_id')
                ->constrained('ticket_types')
                ->restrictOnDelete();

            $table->unsignedInteger('quantity');
            $table->unsignedInteger('unit_price');   // Snapshot at purchase time
            $table->unsignedInteger('subtotal');     // quantity × unit_price

            $table->timestamps();

            $table->index('order_id', 'idx_order_items_order');
            $table->index('ticket_type_id', 'idx_order_items_ticket_type');
        });

        // Platform revenue split per order
        Schema::create('order_commissions', function (Blueprint $table) {
            $table->id();

            // One commission record per order
            $table->foreignId('order_id')
                ->unique()
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->restrictOnDelete();

            $table->unsignedInteger('gross_amount');
            $table->decimal('commission_rate', 5, 2);
            $table->unsignedInteger('commission_amount');   // Platform keeps this
            $table->unsignedInteger('organizer_amount');   // Organizer receives this

            $table->enum('settlement_status', ['pending', 'settled', 'paid_out'])
                ->default('pending');
            $table->timestamp('settled_at')->nullable();
            $table->timestamps();

            $table->index('organization_id', 'idx_commissions_org');
            $table->index('settlement_status', 'idx_commissions_status');
        });

        // Digital ticket records (one per attendee)
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_item_id')
                ->constrained('order_items')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('event_id')
                ->constrained('events')
                ->restrictOnDelete();

            $table->foreignId('ticket_type_id')
                ->constrained('ticket_types')
                ->restrictOnDelete();

            // Unique QR code for scanning at the gate
            $table->string('ticket_code', 50)->unique();
            $table->string('qr_code')->nullable();          // Base64 or URL

            $table->enum('status', ['active', 'used', 'cancelled', 'expired'])
                ->default('active');

            $table->timestamp('checked_in_at')->nullable();
            $table->foreignId('checked_in_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['event_id', 'status'], 'idx_tickets_event_status');
            $table->index('user_id', 'idx_tickets_user');
            $table->index('ticket_code', 'idx_tickets_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('order_commissions');
        Schema::dropIfExists('order_items');
    }
};
