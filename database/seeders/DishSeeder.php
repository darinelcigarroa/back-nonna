<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\DishType;
use Illuminate\Database\Seeder;

class DishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entradaId = DishType::where('name', 'Entrada')->first()->id;
        $platoFuerteId = DishType::where('name', 'Plato Fuerte')->first()->id;
        $postreId = DishType::where('name', 'Postre')->first()->id;
        $bebidaId = DishType::where('name', 'Bebida')->first()->id;

        // Insertar platillos
        Dish::create(['name' => 'Bruschetta', 'description' => 'Pan con tomate y albahaca', 'price' => 5.99, 'dish_type_id' => $entradaId]);
        Dish::create(['name' => 'Nachos con Queso', 'description' => 'Totopos con queso derretido y jalapeños', 'price' => 6.99, 'dish_type_id' => $entradaId]);
        Dish::create(['name' => 'Ensalada César', 'description' => 'Lechuga, crutones y aderezo césar', 'price' => 7.50, 'dish_type_id' => $entradaId]);
        Dish::create(['name' => 'Sopa de Tortilla', 'description' => 'Sopa con tiras de tortilla y aguacate', 'price' => 8.50, 'dish_type_id' => $entradaId]);
        Dish::create(['name' => 'Carpaccio de Res', 'description' => 'Finas láminas de res con alcaparras y queso', 'price' => 9.99, 'dish_type_id' => $entradaId]);

        Dish::create(['name' => 'Lomo en Salsa de Champiñones', 'description' => 'Filete de lomo con salsa cremosa', 'price' => 15.99, 'dish_type_id' => $platoFuerteId]);
        Dish::create(['name' => 'Pechuga Rellena de Queso', 'description' => 'Pechuga de pollo rellena de queso manchego', 'price' => 13.99, 'dish_type_id' => $platoFuerteId]);
        Dish::create(['name' => 'Pasta Alfredo', 'description' => 'Fettuccine con salsa Alfredo y parmesano', 'price' => 12.50, 'dish_type_id' => $platoFuerteId]);
        Dish::create(['name' => 'Salmón a la Parrilla', 'description' => 'Salmón fresco con vegetales asados', 'price' => 17.99, 'dish_type_id' => $platoFuerteId]);
        Dish::create(['name' => 'Tacos al Pastor', 'description' => 'Tacos de cerdo marinados con piña y cebolla', 'price' => 10.99, 'dish_type_id' => $platoFuerteId]);

        Dish::create(['name' => 'Flan Napolitano', 'description' => 'Flan con caramelo', 'price' => 4.99, 'dish_type_id' => $postreId]);
        Dish::create(['name' => 'Pastel de Chocolate', 'description' => 'Bizcocho de chocolate con ganache', 'price' => 5.99, 'dish_type_id' => $postreId]);
        Dish::create(['name' => 'Helado Artesanal', 'description' => 'Helado de diferentes sabores', 'price' => 3.99, 'dish_type_id' => $postreId]);
        Dish::create(['name' => 'Tiramisú', 'description' => 'Postre italiano con café y mascarpone', 'price' => 6.50, 'dish_type_id' => $postreId]);
        Dish::create(['name' => 'Cheesecake de Fresa', 'description' => 'Tarta de queso con fresas', 'price' => 5.50, 'dish_type_id' => $postreId]);

        Dish::create(['name' => 'Jugo de Naranja', 'description' => 'Jugo natural de naranja', 'price' => 2.99, 'dish_type_id' => $bebidaId]);
        Dish::create(['name' => 'Café Americano', 'description' => 'Café negro recién preparado', 'price' => 2.50, 'dish_type_id' => $bebidaId]);
        Dish::create(['name' => 'Té Verde', 'description' => 'Infusión de té verde caliente', 'price' => 2.75, 'dish_type_id' => $bebidaId]);
        Dish::create(['name' => 'Refresco de Cola', 'description' => 'Refresco gaseoso', 'price' => 2.99, 'dish_type_id' => $bebidaId]);
        Dish::create(['name' => 'Limonada', 'description' => 'Bebida refrescante de limón', 'price' => 3.25, 'dish_type_id' => $bebidaId]);
    }
}
