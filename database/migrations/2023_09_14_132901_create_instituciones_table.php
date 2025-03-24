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
        Schema::create('instituciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('name');
            $table->string('email');
            $table->string('tipo');

            $table->string('pais');
            $table->string('estado');
            $table->string('ciudad');
            
            $table->string('domicilio');
            $table->string('latitud');
            $table->string('longitud');
            $table->string('mapa_link');

            
            $table->string('pagina_web')->nullable();            
            $table->string('telefono')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email_contacto')->nullable();

            $table->boolean('nombre_escuela_credencial'); 
            $table->boolean('nombre_escuela_personalizado')->default(false); 
            $table->string('nombre_credencial_escrito')->nullable();            

            $table->string('ubicacion_imagen', 2048)->nullable();
            $table->boolean('portada_oculta')->default(false);

            $table->softDeletes(); // Agrega la columna deleted_at
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instituciones');
    }
};
