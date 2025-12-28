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
        Schema::create('access_policies', function (Blueprint $table) {
            $table->id();

            $table->foreignId('permission_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->morphs('policyable'); // policyable_id, policyable_type

            $table->boolean('is_allowed')->default(true);

            $table->json('ip_ranges')->nullable();

            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();

            $table->json('days_of_week')->nullable(); // [1..7]

            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            $table->string('timezone')->default(config('app.timezone'));

            $table->integer('priority')->default(0);

            $table->timestamps();

            $table->index(
                ['permission_id', 'priority'],
                'access_policy_lookup_idx'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
