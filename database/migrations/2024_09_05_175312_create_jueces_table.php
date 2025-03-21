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
        Schema::create('jueces', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('registro_juez_id'); //--> crear columna dentro de la tabla jueces            
            $table->foreign('registro_juez_id')->references('id')->on('registro_jueces')->constrained(); // --> referenciar columna de ID dentro de la tabla registro_jueces 

            $table->foreignId('user_id')->constrained();

            $table->string('name');
            $table->string('lastname');
            $table->string('telefono')->nullable();
            $table->string('email');

            $table->softDeletes(); // Agrega la columna deleted_at
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jueces');
    }
};
