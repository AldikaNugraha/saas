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
        Schema::create('categoricals', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('project_id')->constrained("projects")->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->timestampTz("created_at")->useCurrent();
            $table->timestampTz("updated_at")->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categoricals');
    }
};
