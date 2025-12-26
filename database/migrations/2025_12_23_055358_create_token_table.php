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

            /* ==============================
             | Primary Identity
             |============================== */
            $table->bigIncrements('id');

            /* ==============================
             | Account Relation
             |============================== */
            $table->unsignedBigInteger('user_id');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            /* ==============================
             | Token Security
             |============================== */

            // هش شده توکن (مثلاً sha256 یا bcrypt)
            $table->string('token_hash', 255)->unique();

            // نوع توکن (قابل توسعه)
            $table->string('token_type', 30)
                ->default('remember')
                ->comment('remember | session | api | otp');

            /* ==============================
             | Device & Environment Info
             |============================== */

            // اگر خواستی fingerprint داشته باشی
            $table->string('device_id', 100)->nullable()->index();

            $table->string('device_name', 100)->nullable()
                ->comment('e.g. iPhone 14, Windows PC');

            $table->string('platform', 50)->nullable()
                ->comment('ios | android | windows | mac | linux');

            $table->string('browser', 50)->nullable()
                ->comment('Chrome, Firefox, Safari');

            $table->ipAddress('ip_address')->nullable();

            $table->text('user_agent')->nullable();

            /* ==============================
             | Lifecycle & Security Control
             |============================== */

            // آخرین استفاده واقعی
            $table->timestamp('last_used_at')->nullable();

            // تاریخ انقضا
            $table->timestamp('expires_at')->index();

            // اگر مقدار داشته باشد یعنی توکن باطل شده
            $table->timestamp('revoked_at')->nullable()->index();

            /* ==============================
             | Audit
             |============================== */
            $table->timestamps();

            /* ==============================
             | Composite Indexes (Performance)
             |============================== */

            $table->index(['user_id', 'token_type']);
            $table->index(['user_id', 'revoked_at']);
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
