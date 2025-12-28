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
        Schema::create('groupables', function (Blueprint $table) {
            $table->id();

            $table->foreignId('group_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedBigInteger('groupable_id');
            $table->string('groupable_type');

            $table->timestamps();

            $table->unique(
                ['group_id', 'groupable_id', 'groupable_type'],
                'groupables_unique'
            );

            $table->index(
                ['groupable_id', 'groupable_type'],
                'groupables_morph_index'
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
