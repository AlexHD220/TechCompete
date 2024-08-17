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
            //$table->unsignedBigInteger('user_id'); //Llave foranea de esta columna
            $table->foreignID('user_id')->constrained();
            $table->string('nombre')->unique();


            //-> onDelete('cascade') // se aplica a la llave foranea de la tabla dependiente para que se elimine cuando se elimina el id referenciado

            $table->unsignedBigInteger('asesor_id'); //--> crear columna dentro de la tabla competencias
            $table->foreign('asesor_id')->references('id')->on('asesores')->constrained(); //--> referenciar columna de ID dentro de la tabla competencias 
            
            $table->foreignID('competencia_id')->constrained();
            
            $table->foreignID('categoria_id')->constrained();
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
