<?php

namespace Database\Seeders;

use App\Models\Stash;
use Illuminate\Database\Seeder;

class StashSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stashes = [
            ['name' => 'Reserva de emergência', 'amount' => 200.50, 'goal_amount' => 30000, 'purpose' => 'Quando houver algum problema'],
            ['name' => 'Casamento', 'amount' => 50000, 'goal_amount' => 250000, 'purpose' => 'Dinheiro para o casamento']
        ];

        foreach ($stashes as $stash) {
            Stash::create(array_merge($stash));
        }
    }
}
