<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    public function run(): void
    {
        $banks = [
            ['name' => 'Nubank',          'color' => '#820AD1'],
            ['name' => 'Itaú',            'color' => '#EC7000'],
        ];

        foreach ($banks as $bank) {
            Bank::firstOrCreate(['name' => $bank['name']], $bank);
        }
    }
}
