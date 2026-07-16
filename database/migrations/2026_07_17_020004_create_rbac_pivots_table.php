<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Global roles (super_admin, customer)
        // Organization-scoped roles live in organization_user (owner, manager, staff)
        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('role_id')
                ->constrained('roles')
                ->cascadeOnDelete();

            $table->primary(['user_id', 'role_id']);
        });

        // Organization membership with org-scoped role
        Schema::create('organization_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->enum('role', ['owner', 'manager', 'staff']);
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();

            // One user can only have one role per organization
            $table->unique(['organization_id', 'user_id'], 'unique_org_member');
            $table->index(['organization_id', 'role'], 'idx_org_user_role');
        });

        // Verification documents uploaded during registration
        Schema::create('organization_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->cascadeOnDelete();
            $table->string('type', 50);             // e.g., 'registration_letter', 'id_card'
            $table->string('file_path');
            $table->string('original_name');
            $table->unsignedInteger('file_size');   // bytes
            $table->timestamps();

            $table->index('organization_id', 'idx_org_docs_org');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_documents');
        Schema::dropIfExists('organization_user');
        Schema::dropIfExists('role_user');
    }
};
