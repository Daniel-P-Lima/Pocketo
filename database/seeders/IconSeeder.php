<?php

namespace Database\Seeders;

use App\Models\Icon;
use Illuminate\Database\Seeder;

class IconSeeder extends Seeder
{
    public function run(): void
    {
        $icons = [
            "\u{1F354}", // 🍔
            "\u{1F697}", // 🚗
            "\u{1F3E0}", // 🏠
            "\u{2764}\u{FE0F}", // ❤️
            "\u{1F4DA}", // 📚
            "\u{1F3AE}", // 🎮
            "\u{1F455}", // 👕
            "\u{1F4C4}", // 📄
            "\u{1F4E6}", // 📦
            "\u{1F4BC}", // 💼
            "\u{1F4BB}", // 💻
            "\u{1F4C8}", // 📈
            "\u{1F6D2}", // 🛒
            "\u{2708}\u{FE0F}", // ✈️
            "\u{1F3B5}", // 🎵
            "\u{26BD}", // ⚽
            "\u{1F48A}", // 💊
            "\u{1F415}", // 🐕
            "\u{1F381}", // 🎁
            "\u{1F4A1}", // 💡
        ];

        foreach ($icons as $icon) {
            Icon::firstOrCreate(['icon' => $icon]);
        }
    }
}