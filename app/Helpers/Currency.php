<?php

namespace App\Helpers;

class Currency
{
    public static function toCentavos(string $value): int
    {
        $clean = str_replace('.', '', $value);
        $clean = str_replace(',', '.', $clean);

        return (int) round((float) $clean * 100);
    }

    public static function format(int $centavos): string
    {
        return 'R$ ' . number_format($centavos / 100, 2, ',', '.');
    }
}
