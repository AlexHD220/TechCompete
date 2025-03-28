<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Categoria::create([
            'name' => 'Mini Sumo',
            'tipo' => 'Equipo',
            'descripcion' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam eget justo vel libero gravida commodo. Duis in ante eget ipsum tristique convallis. Quisque fermentum turpis ac mauris convallis, sit amet eleifend elit commodo. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla facilisi. Maecenas auctor purus vel felis congue, a bibendum nunc pharetra. Integer id tortor nec sem tincidunt malesuada.',
       ]);

        Categoria::create([
            'name' => 'Sigue Lineas',
            'tipo' => 'Equipo',
            'descripcion' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam eget justo vel libero gravida commodo. Duis in ante eget ipsum tristique convallis. Quisque fermentum turpis ac mauris convallis, sit amet eleifend elit commodo. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla facilisi. Maecenas auctor purus vel felis congue, a bibendum nunc pharetra. Integer id tortor nec sem tincidunt malesuada.',
        ]);

        Categoria::create([
            'name' => 'Robotica',
            'tipo' => 'Proyecto',
            'descripcion' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam eget justo vel libero gravida commodo. Duis in ante eget ipsum tristique convallis. Quisque fermentum turpis ac mauris convallis, sit amet eleifend elit commodo. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla facilisi. Maecenas auctor purus vel felis congue, a bibendum nunc pharetra. Integer id tortor nec sem tincidunt malesuada.',
        ]);
        
        Categoria::create([
            'name' => 'Animacion',
            'tipo' => 'Proyecto',
            'descripcion' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam eget justo vel libero gravida commodo. Duis in ante eget ipsum tristique convallis. Quisque fermentum turpis ac mauris convallis, sit amet eleifend elit commodo. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla facilisi. Maecenas auctor purus vel felis congue, a bibendum nunc pharetra. Integer id tortor nec sem tincidunt malesuada.',
       ]);

        Categoria::create([
            'name' => 'Cuento Cientifico',
            'tipo' => 'Proyecto',
            'descripcion' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam eget justo vel libero gravida commodo. Duis in ante eget ipsum tristique convallis. Quisque fermentum turpis ac mauris convallis, sit amet eleifend elit commodo. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla facilisi. Maecenas auctor purus vel felis congue, a bibendum nunc pharetra. Integer id tortor nec sem tincidunt malesuada.',
        ]);

        Categoria::factory()->count(1)->create(); //--> generar informacion falsa
    }
}
