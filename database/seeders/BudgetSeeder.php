<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Budget;

class BudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all()->keyBy('name');
        $category = fn (string $name) => $categories[$name]->id ?? null;

        $month = now()->month;
        $year  = now()->year;

        $budgets = [
            ['category_id' => $category('Alimentação'), 'amount' => 700,  'month' => $month, 'year' => $year],
            ['category_id' => $category('Transporte'),  'amount' => 250,  'month' => $month, 'year' => $year],
            ['category_id' => $category('Lazer'),       'amount' => 500,  'month' => $month, 'year' => $year],
            ['category_id' => $category('Contas'),      'amount' => 2000,  'month' => $month, 'year' => $year],
        ];

        foreach ($budgets as $budget) {
            Budget::firstOrCreate(
                ['category_id' => $budget['category_id'], 'month' => $budget['month'], 'year' => $budget['year']],
                ['amount' => $budget['amount']]
            );
        }
    }
}
