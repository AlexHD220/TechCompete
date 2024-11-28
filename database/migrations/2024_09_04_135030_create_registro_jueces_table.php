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
        Schema::create('registro_jueces', function (Blueprint $table) {
            $table->id();
            
            $table->string('codigo')->unique();
            $table->boolean('validado')->default(false);

            $table->unsignedBigInteger('creado_by'); //--> crear columna dentro de la tabla registro jueces            
            $table->foreign('creado_by')->references('id')->on('users')->constrained(); // --> referenciar columna de ID dentro de la tabla users 
            
            $table->timestamp('creacion_date');
            $table->timestamp('validado_date')->nullable();
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_jueces');
    }
};
