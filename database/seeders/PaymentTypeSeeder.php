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

        DB::table('payment_types')->insert($paymentTypes);
    }
}
