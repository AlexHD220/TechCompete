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
        Schema::create('jueces_competencias', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('competencia_categoria_id')->constrained();            
            $table->unsignedBigInteger('juez_id'); //--> crear columna dentro de esta tabla            
            $table->foreign('juez_id')->references('id')->on('jueces')->constrained(); // --> referenciar columna de ID dentro de la tabla jueces 

            $table->softDeletes();
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jueces_competencias');
    }
};
