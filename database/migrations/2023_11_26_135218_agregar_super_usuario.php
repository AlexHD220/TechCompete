<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Agregar registro de Super Usuarios base de datos 
        User::factory()->withPersonalTeam()->create([
            'name' => 'Alejandro Hernández',
            'rol' => 1,
            'email' => 'superadmin@techcompete.com',
            'password' =>Hash::make('Pruebas.tc23'),
       ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar el registro agregado si es necesario
        User::where('name', 'Alejandro Hernández')->delete();
    }
};
