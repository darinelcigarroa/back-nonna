<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(range(1,20) as $number) {
            Table::create([
                'name' => "Mesa $number",
                'capacity' => random_int(1, 5),
                'status' => true,
                'in_use' => false
            ]);
        }
    }
}
