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
        Schema::create('participantes', function (Blueprint $table) {
            $table->id();
            
            //$table->morphs('participable'); // Crea columnas participable_id y participable_type
            $table->unsignedBigInteger('participable_id');
            $table->string('participable_type');
            $table->unsignedBigInteger('num_participacion');

            $table->string('name');
            $table->string('lastname');

            $table->string('credencial_path', 2048)->nullable();
            $table->string('nombre_original_credencial')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participantes');
    }
};
