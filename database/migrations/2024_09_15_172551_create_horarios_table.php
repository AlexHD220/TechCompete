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
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->string('evento');
            $table->time('hora_inicio');
            
            $table->foreignId('competencia_id')->constrained();
            $table->foreignId('competencia_categoria_id')->constrained();

            $table->string('descripcion')->nullable();
            $table->string('lugar');

            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
