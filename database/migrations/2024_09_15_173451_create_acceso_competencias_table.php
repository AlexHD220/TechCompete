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
        Schema::create('acceso_competencias', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('competencia_categoria_id')->constrained();
            $table->foreignId('user_id')->constrained(); //(juez_id/staff_id)

            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acceso_competencias');
    }
};
