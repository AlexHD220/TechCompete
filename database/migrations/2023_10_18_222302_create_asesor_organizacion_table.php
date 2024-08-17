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
        Schema::create('asesor_organizacion', function (Blueprint $table) {
            //$table->foreignID('asesor_id')->constrained('asesores'); // <---- reestriccion para no meter ids que no existan
            
            //$table->foreign('asesor_id')->references('id')->on('asesores'); //--> referenciar columna de ID dentro de la tabla competencias 
            
            $table->unsignedBigInteger('asesor_id'); //--> crear columna dentro de la tabla competencias
 
            $table->foreign('asesor_id')->references('id')->on('asesores')->constrained(); //--> referenciar columna de ID dentro de la tabla competencias 


            $table->unsignedBigInteger('organizacion_id');
            $table->foreign('organizacion_id')->references('id')->on('organizaciones')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asesor_organizacion');
    }
};
