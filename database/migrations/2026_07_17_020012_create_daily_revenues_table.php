<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daily_revenues', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            
            // If organization_id is null, it represents platform-level totals
            // Otherwise, it represents the organization's totals for that day
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            
            $table->decimal('gross_revenue', 15, 2)->default(0); // Subtotal (Before platform fees and commissions)
            $table->decimal('platform_fee', 15, 2)->default(0);  // Fixed platform fees
            $table->decimal('commission_amount', 15, 2)->default(0); // Platform percentage commission
            $table->decimal('net_revenue', 15, 2)->default(0); // Organizer payout (Gross - Commission)
            
            $table->integer('tickets_sold')->default(0);
            
            $table->timestamps();

            $table->unique(['date', 'organization_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_revenues');
    }
};
