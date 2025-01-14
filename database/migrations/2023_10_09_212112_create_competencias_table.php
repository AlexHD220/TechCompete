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
            $table->date('fecha_fin');
            $table->unsignedInteger('duracion');            
            $table->string('tipo');            
            $table->string('sede');
            $table->string('ubicacion');

            $table->string('latitud');
            $table->string('longitud');
            $table->string('mapa_link');

            $table->unsignedInteger('cant_participaciones')->default(0);
            $table->string('ubicacion_imagen', 2048);

            //$table->boolean('publicada')->nullable()->default(false);
            $table->boolean('publicada')->default(false);
            $table->boolean('oculta')->default(false);

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
