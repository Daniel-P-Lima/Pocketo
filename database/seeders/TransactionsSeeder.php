<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Transaction;

class TransactionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all()->keyBy('name');
        $category = fn (string $name) => $categories[$name]->id ?? null;

        $transactions = [
            [
                'category_id' => $category('Lazer'), 'type' => 'expense', 'amount' => 250.00, 'description' => 'Janta dia dos namorados', 'notes' => 'Jantar no Carlo', 'date' => '2026-02-20 14:24:53'
            ],
            [
                'category_id' => $category('Educação'), 'type' => 'expense', 'amount' => 1250.00, 'description' => 'Curso de python', 'notes' => 'Python 101', 'date' => '2026-02-20 14:24:53'
            ],
            [
                'category_id' => $category('Salário'), 'type' => 'income', 'amount' => 5250.00, 'description' => 'Salário', 'notes' => 'Weega', 'date' => '2026-02-20 14:24:53'
            ],
            [
                'category_id' => $category('Freelance'), 'type' => 'income', 'amount' => 1250.00, 'description' => 'Freela', 'notes' => 'Site pro Cliente', 'date' => '2026-02-20 14:24:53'
            ],
            [
                'category_id' => $category('Bitcoin'), 'type' => 'income', 'amount' => 500.00, 'description' => 'Bitcoin venda', 'notes' => 'Subiu e vendi', 'date' => '2026-02-20 14:24:53'
            ],
        ];

        foreach ($transactions as $transaction) {
            Transaction::firstOrCreate(
                [...$transaction]
            );
        }
    }
}
