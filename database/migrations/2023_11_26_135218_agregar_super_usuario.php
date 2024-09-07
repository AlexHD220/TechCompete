<?php

use App\Models\Administrador;
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
        // Crear el Administrador
        //$administrador = Administrador::create([
        $administrador = Administrador::factory()->create([
            'nombre' => 'Alejandro Hernández',
            // Otros campos necesarios
        ]);
        
        // Crear el Administrador
        //$administrador = Administrador::factory()->create();

        // Agregar registro de Super Usuarios base de datos 
        //User::factory()->withPersonalTeam()->create([
        User::create([
            //'name' => 'Alejandro Hernández',
            'name' => $administrador->nombre,
            'rol' => 1,
            'roleable_id' => $administrador->id,
            'roleable_type' => get_class($administrador),
            'email' => 'superadmin@techcompete.com',
            'email_verified_at' => now(),
            'password' =>Hash::make('Pruebas.tc23'),
            // ---> TEMPORAL
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
