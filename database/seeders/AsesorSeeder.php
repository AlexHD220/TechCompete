<?php

namespace Database\Seeders;

use App\Models\Asesor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AsesorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Asesor::factory()->count(10)->create(); //--> generar informacion falsa
    }
}
