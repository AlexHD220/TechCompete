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
        Schema::create('evaluaciones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('competencia_categoria_id')->constrained();
            $table->foreignId('proyecto_id')->constrained();

            $table->unsignedBigInteger('juez_id'); //--> crear columna dentro de la tabla jueces            
            $table->foreign('juez_id')->references('id')->on('jueces')->constrained(); // --> referenciar columna de ID dentro de la tabla jueces 
           
            $table->unsignedBigInteger('innovacion')->default(0);
            $table->unsignedBigInteger('creatividad')->default(0);
            $table->unsignedBigInteger('aplicabilidad')->default(0);
            $table->unsignedBigInteger('objetivos')->default(0);
            $table->unsignedBigInteger('calidad')->default(0);
            $table->unsignedBigInteger('investigacion_fundamentos')->default(0);
            $table->unsignedBigInteger('exposicion_presentación')->default(0);
            $table->unsignedBigInteger('impacto_social_ambiental')->default(0);
            $table->unsignedBigInteger('viabilidad')->default(0);
            $table->unsignedBigInteger('producto')->default(0);

            $table->text('comentarios')->nullable();
            $table->unsignedBigInteger('puntaje_total')->default(0);

            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluaciones');
    }
};
