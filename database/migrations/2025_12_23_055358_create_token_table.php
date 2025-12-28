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
        Schema::create('tokens', function (Blueprint $table) {
// Identity
            $table->id();

// Relations
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

// Token security
            $table->char('token_hash', 64)->index();
            $table->string('token_type', 30)->index();
            $table->string('name', 100)->nullable();

// Authorization
            $table->json('abilities')->nullable();

// Device info
            $table->string('device_id', 100)->nullable()->index();
            $table->string('device_name', 100)->nullable();
            $table->string('platform', 50)->nullable();
            $table->string('browser', 50)->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();

// Lifecycle
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamp('refresh_expires_at')->nullable();
            $table->timestamp('revoked_at')->nullable()->index();

// Meta
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tokens');
    }
};
