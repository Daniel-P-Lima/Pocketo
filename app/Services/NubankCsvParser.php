<?php

namespace App\Services;

use App\Helpers\Currency;
use Carbon\Carbon;
use InvalidArgumentException;
use SplFileObject;

/**
 * Lê a fatura exportada pelo Nubank (colunas: date, title, amount).
 * Valores positivos são compras (despesa) e negativos são pagamentos/estornos (receita).
 */
class NubankCsvParser
{
    private const REQUIRED_COLUMNS = ['date', 'title', 'amount'];

    /**
     * O Nubank nomeia o arquivo com a data de vencimento (ex: Nubank_2026-10-14.csv).
     * Retorna o mês no formato AAAA-MM, ou null se o nome não tiver data.
     */
    public function guessReferenceMonth(string $filename): ?string
    {
        if (! preg_match('/(\d{4})-(\d{2})-\d{2}/', $filename, $matches) || $matches[2] < 1 || $matches[2] > 12) {
            return null;
        }

        return "{$matches[1]}-{$matches[2]}";
    }

    /**
     * @return array<int, array{date: string, description: string, amount: float, type: string}>
     */
    public function parse(string $path): array
    {
        $file = new SplFileObject($path);

        $header = $file->fgetcsv(escape: '');

        if (! $header || $header === [null]) {
            throw new InvalidArgumentException('O arquivo está vazio.');
        }

        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
        $header = array_map(fn ($column) => strtolower(trim($column)), $header);

        if (array_diff(self::REQUIRED_COLUMNS, $header)) {
            throw new InvalidArgumentException('Formato inválido: o CSV precisa ter as colunas date, title e amount.');
        }

        $columns = array_flip($header);
        $rows = [];
        $line = 1;

        while (! $file->eof()) {
            $values = $file->fgetcsv(escape: '');
            $line++;

            if (! $values || $values === [null]) {
                continue;
            }

            $date = trim($values[$columns['date']] ?? '');
            $title = trim($values[$columns['title']] ?? '');
            $amount = trim($values[$columns['amount']] ?? '');

            try {
                $date = Carbon::createFromFormat('!Y-m-d', $date)->toDateString();
            } catch (\Throwable) {
                throw new InvalidArgumentException("Data inválida na linha {$line}: \"{$date}\".");
            }

            if ($title === '' || ! preg_match('/^-?\s*[\d.]+(,\d{1,2})?$/', $amount)) {
                throw new InvalidArgumentException("Linha {$line} inválida.");
            }

            $value = Currency::toCentavos(str_replace(' ', '', $amount)) / 100;

            if ($value == 0) {
                continue;
            }

            $rows[] = [
                'date' => $date,
                'description' => mb_substr($title, 0, 255),
                'amount' => abs($value),
                'type' => $value < 0 ? 'income' : 'expense',
            ];
        }

        if (! $rows) {
            throw new InvalidArgumentException('Nenhuma transação encontrada no arquivo.');
        }

        return $rows;
    }
}
