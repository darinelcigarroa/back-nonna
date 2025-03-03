<?php

namespace Database\Seeders;

use App\Models\DishType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DishTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dishTypes = [
            ['name' => 'Entrada', 'description' => 'Plato que se sirve antes del plato fuerte.'],
            ['name' => 'Sopa', 'description' => 'Plato líquido caliente o frío que suele tomarse como primer plato.'],
            ['name' => 'Plato Fuerte', 'description' => 'Plato principal de la comida con mayor cantidad de ingredientes.'],
            ['name' => 'Postre', 'description' => 'Platillo dulce o ligero que se sirve al final de la comida.'],
            ['name' => 'Bebida', 'description' => 'Líquido para acompañar la comida, puede ser frío o caliente.'],
        ];

        foreach ($dishTypes as $type) {
            DishType::updateOrCreate(
                ['name' => $type['name']], // Condición para evitar duplicados
                ['description' => $type['description']]
            );
        }
    }
}
