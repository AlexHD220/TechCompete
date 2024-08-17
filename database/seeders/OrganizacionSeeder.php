<?php

namespace Database\Seeders;

use App\Models\Organizacion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Organizacion::factory()->count(8)->create();
    }
}
