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
        Schema::create('rasters', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('name');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('source'); #satelit atau drone
            $table->string('path'); #path tifnya
            $table->integer('band')->nullable(); #band nya RGB/NIR/NDVI
            $table->double('north')->nullable();
            $table->double('south')->nullable();
            $table->double('east')->nullable();
            $table->double('west')->nullable(); 
            $table->timestampTz("created_at")->useCurrent();
            $table->timestampTz(column: "updated_at")->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rasters');
    }
};
