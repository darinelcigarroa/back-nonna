<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentTypes = [
            ['name' => 'Efectivo'],
            ['name' => 'Tarjeta de crédito'],
            ['name' => 'Tarjeta de débito'],
            ['name' => 'Transferencia'],
            ['name' => 'Otros'],
        ];
        
        foreach ($paymentTypes as $paymentType) {
            DB::table('payment_types')->updateOrInsert(
                ['name' => $paymentType['name']], // Condición para verificar si ya existe
                ['name' => $paymentType['name']]  // Los valores a insertar o actualizar
            );
        }
        
    }
}
