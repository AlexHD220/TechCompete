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
        Schema::create('competencia_categorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competencia_id')->constrained();
            $table->foreignId('categoria_id')->constrained();
            $table->string('nivel');

            $table->unsignedBigInteger('costo');

            $table->unsignedBigInteger('limite_inscritos');
            $table->unsignedBigInteger('min_participantes');
            $table->unsignedBigInteger('max_participantes');

            $table->unsignedBigInteger('total_inscritos')->default(0);
            
            $table->softDeletes();
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competencia_categorias');
    }
};
