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
        Schema::create('competencias', function (Blueprint $table) {

            $table->id();
            $table->string('name') -> unique();
            $table->text('descripcion');

            $table->date('fecha');
            $table->unsignedInteger('duracion');
            $table->string('sede');
            $table->string('tipo');

            $table->unsignedInteger('cant_participantes')->default(0);
            $table->string('imagen_path', 2048)->nullable();

            $table->softDeletes(); // Agrega la columna deleted_at
            //$table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competencias');
    }
};
