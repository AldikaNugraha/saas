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
        Schema::create('numericals', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('name');
            $table->foreignId('categorical_id')->constrained("categoricals")->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('type');
            $table->unsignedBigInteger('num_field');
            $table->timestampTz("created_at")->useCurrent();
            $table->timestampTz( "updated_at")->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('numericals');
    }
};
