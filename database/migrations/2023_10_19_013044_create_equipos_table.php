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
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->foreignId('competencia_id')->constrained();
            $table->foreignId('competencia_categoria_id')->constrained();
            $table->boolean('pagado')->default(false);

            $table->string('comprobante_path', 2048)->nullable();
            $table->string('nombre_original_comprobante')->nullable();
                        
            $table->unsignedBigInteger('autorizado_by')->nullable(); //--> crear columna dentro de la tabla equipos            
            $table->foreign('autorizado_by')->references('id')->on('users')->constrained(); // --> referenciar columna de ID dentro de la tabla users 


            $table->string('carta_path', 2048)->nullable();
            $table->string('nombre_original_carta')->nullable();

            $table->unsignedBigInteger('asesor_id'); //--> crear columna dentro de la tabla asesores            
            $table->foreign('asesor_id')->references('id')->on('asesores')->constrained(); // --> referenciar columna de ID dentro de la tabla asesores 

            $table->unsignedBigInteger('institucion_id')->nullable(); //--> crear columna dentro de la tabla instituciones            
            $table->foreign('institucion_id')->references('id')->on('instituciones')->constrained(); // --> referenciar columna de ID dentro de la tabla instituciones 
            $table->boolean('inst_independiente')->default(false);
            $table->string('inst_nombre')->nullable();

            $table->unsignedBigInteger('num_equipo');            
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};
