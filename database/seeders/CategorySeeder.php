<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Icon;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $icons = Icon::all()->keyBy('icon');
        $icon = fn (string $emoji) => $icons[$emoji]->id ?? null;

        $categories = [
            // Expense categories
            ['name' => 'Alimentação', 'icon_id' => $icon("\u{1F354}"),        'color' => '#EF4444', 'type' => 'expense'],
            ['name' => 'Transporte',  'icon_id' => $icon("\u{1F697}"),        'color' => '#3B82F6', 'type' => 'expense'],
            ['name' => 'Moradia',     'icon_id' => $icon("\u{1F3E0}"),        'color' => '#8B5CF6', 'type' => 'expense'],
            ['name' => 'Saúde',       'icon_id' => $icon("\u{2764}\u{FE0F}"), 'color' => '#10B981', 'type' => 'expense'],
            ['name' => 'Educação',    'icon_id' => $icon("\u{1F4DA}"),        'color' => '#6366F1', 'type' => 'expense'],
            ['name' => 'Lazer',       'icon_id' => $icon("\u{1F3AE}"),        'color' => '#EC4899', 'type' => 'expense'],
            ['name' => 'Roupas',      'icon_id' => $icon("\u{1F455}"),        'color' => '#F59E0B', 'type' => 'expense'],
            ['name' => 'Contas',      'icon_id' => $icon("\u{1F4C4}"),        'color' => '#64748B', 'type' => 'expense'],
            ['name' => 'Outros',      'icon_id' => $icon("\u{1F4E6}"),        'color' => '#9CA3AF', 'type' => 'both'],

            // Income categories
            ['name' => 'Salário',       'icon_id' => $icon("\u{1F4BC}"), 'color' => '#10B981', 'type' => 'income'],
            ['name' => 'Freelance',     'icon_id' => $icon("\u{1F4BB}"), 'color' => '#06B6D4', 'type' => 'income'],
            ['name' => 'Investimentos', 'icon_id' => $icon("\u{1F4C8}"), 'color' => '#EAB308', 'type' => 'income'],
            ['name' => 'Bitcoin',       'icon_id' => $icon("\u{1F4C8}"), 'color' => '#ffbf00', 'type' => 'income'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                [...$category, 'is_default' => true]
            );
        }
    }
}