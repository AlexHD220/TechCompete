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
            //$table->string('usuario')->unique();
            $table->string('nombre');
            $table->string('correo');
            //$table->unsignedInteger('telefono')->nullable();
            $table->string('telefono')->nullable();
            //$table->string('escuela')->nullable();
            //$table->string('pass');

            //$table->timestamps();

            //$table->softDeletes(); // Borrado logico de datos
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
