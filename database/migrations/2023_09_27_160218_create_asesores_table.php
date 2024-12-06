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
        Schema::create('asesores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();

            $table->unsignedBigInteger('institucion_id')->nullable(); //--> crear columna dentro de la tabla instituciones            
            $table->foreign('institucion_id')->references('id')->on('instituciones')->constrained(); // --> referenciar columna de ID dentro de la tabla instituciones 
            $table->boolean('inst_independiente')->default(false);
            $table->string('inst_nombre')->nullable();

            $table->string('name');
            $table->string('lastname');
            $table->string('telefono')->nullable();
            $table->string('email');

            $table->string('identificacion_path', 2048)->nullable();
            $table->string('nombre_original_identificacion')->nullable();

            $table->softDeletes(); // Agrega la columna deleted_at
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asesores');
    }
};
