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
        Schema::create('diffnumericals', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('name');
            $table->foreignId('categorical_id')->constrained("categoricals")->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('type_value');
            $table->integer('day');
            $table->integer('month');
            $table->integer('year');
            $table->timestampTz("created_at")->useCurrent();
            $table->timestampTz(column: "updated_at")->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diffnumerical');
    }
};
