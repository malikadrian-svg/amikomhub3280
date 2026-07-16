<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Ticket types replace the single price/stock columns on events.
     * This enables per-event pricing tiers: VIP, Regular, Early Bird, etc.
     * quantity_sold is a counter cache — incremented on each order completion.
     */
    public function up(): void
    {
        Schema::create('ticket_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')
                ->constrained('events')
                ->cascadeOnDelete();
            $table->string('name');               // "Regular", "VIP", "Early Bird"
            $table->text('description')->nullable();
            $table->unsignedInteger('price');     // IDR, smallest unit (no decimals for IDR)
            $table->unsignedInteger('quantity');  // Total tickets available
            $table->unsignedInteger('quantity_sold')->default(0); // Counter cache
            $table->unsignedInteger('max_per_order')->default(5);
            $table->dateTime('sale_start')->nullable();
            $table->dateTime('sale_end')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('event_id', 'idx_ticket_types_event');
            $table->index(['event_id', 'is_active'], 'idx_ticket_types_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_types');
    }
};
