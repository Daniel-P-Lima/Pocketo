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

    /**
     * Formata um valor em reais, do jeito que é guardado no banco (ex: "140.41" -> "R$ 140,41").
     */
    public static function format(float|int|string $reais): string
    {
        return 'R$ ' . number_format((float) $reais, 2, ',', '.');
    }
}
