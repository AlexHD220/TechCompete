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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table -> unsignedInteger('rol');
            //$table->morphs('roleable'); // Crea columnas roleable_id y roleable_type
            $table->string('name');
            $table->string('lastname')->nullable();
            //$table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('telefono')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            //$table->boolean('change_password')->default(false);
            $table->rememberToken();
            //$table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->softDeletes();
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
