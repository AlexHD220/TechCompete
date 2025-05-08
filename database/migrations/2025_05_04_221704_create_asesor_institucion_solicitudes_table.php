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
        Schema::create('asesor_institucion_solicitudes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('asesor_id'); //--> crear columna dentro de la tabla asesores            
            $table->foreign('asesor_id')->references('id')->on('asesores')->constrained(); // --> referenciar columna de ID dentro de la tabla asesores 

            $table->unsignedBigInteger('institucion_id'); //--> crear columna dentro de la tabla instituciones            
            $table->foreign('institucion_id')->references('id')->on('instituciones')->constrained(); // --> referenciar columna de ID dentro de la tabla instituciones 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asesor_institucion_solicitudes');
    }
};
