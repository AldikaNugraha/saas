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
            $table->foreignId('vector_id')->constrained("vectors")->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->string('pj_blok')->nullable();
            $table->float('area')->nullable();
            $table->integer('num_tree')->nullable();
            $table->boolean('is_research')->nullable();
            $table->boolean('is_panen')->nullable();
            $table->boolean('is_pupuk')->nullable();
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
