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
        Schema::create('categoria_proyecto', function (Blueprint $table) {
            $table->id();

            $table->foreignID('proyecto_id')->constrained()->onDelete('cascade'); // ->constrained() = reestriccion para no meter ids que no existan
            
            $table->foreignID('categoria_id')->constrained(); //reestriccion para no meter ids que no existan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categoria_proyecto');
    }
};
