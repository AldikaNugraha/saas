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
        Schema::create('numerical_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diffnumerical_id')->constrained("diffnumericals")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('type_id')->constrained("types")->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestampTz("created_at")->useCurrent();
            $table->timestampTz("updated_at")->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('numerical_types');
    }
};
