<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Evolves the old `partners` table concept into a full SaaS organization model.
     * Key design decisions:
     *  - owner_id links the founding user; SET NULL on delete preserves org data even
     *    if the owner account is deleted (edge case for compliance).
     *  - commission_rate overrides the platform default when set; null = use platform default.
     *  - average_rating + total_reviews are counter caches, updated by model observers.
     *  - social_media stored as JSON for flexibility (arbitrary provider list).
     */
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();

            // The user who registered this organization
            $table->foreignId('owner_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo_path')->nullable();
            $table->string('banner_path')->nullable();
            $table->text('description')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('website')->nullable();
            $table->text('address')->nullable();

            // JSON: {"instagram":"url","twitter":"url","facebook":"url"}
            $table->json('social_media')->nullable();

            // Approval lifecycle
            $table->enum('status', ['pending', 'approved', 'rejected', 'suspended'])
                ->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Null = use platform default from platform_settings
            $table->decimal('commission_rate', 5, 2)->nullable();

            // Counter caches (updated by observers, never queried live)
            $table->decimal('average_rating', 3, 2)->default(0.00);
            $table->unsignedInteger('total_reviews')->default(0);

            $table->timestamps();

            $table->index('status');
            $table->index('owner_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
