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
        Schema::create('categoria_competencia', function (Blueprint $table) {
            $table->id();

            $table->foreignID('competencia_id')->constrained()->onDelete('cascade'); // ->constrained() = reestriccion para no meter ids que no existan

            $table->foreignID('categoria_id')->constrained(); //reestriccion para no meter ids que no existan

            $table->softDeletes();
            
            //$table->foreign('competencia_id')->references('id')->on('competencias'); //--> referenciar columna de ID dentro de la tabla competencias 

            //$table->unsignedBigInteger('categoria_id');
            //$table->foreign('categoria_id')->references('id')->on('categorias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categoria_competencia');
    }
};
