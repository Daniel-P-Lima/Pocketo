<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Carbon;

class TransactionsSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy('name');
        $cat = fn (string $name) => $categories[$name]->id ?? null;

        // Dados por mês: [meses atrás, [transações]]
        // Receita varia pouco, despesas variam bastante para gráfico interessante
        $months = [
            5 => [ // Setembro/2025
                ['category_id' => $cat('Salário'),      'type' => 'income',  'amount' => 5250,  'description' => 'Salário',            'date' => '05'],
                ['category_id' => $cat('Freelance'),    'type' => 'income',  'amount' => 800,   'description' => 'Freela site',        'date' => '12'],
                ['category_id' => $cat('Alimentação'),  'type' => 'expense', 'amount' => 620,   'description' => 'Supermercado',       'date' => '08'],
                ['category_id' => $cat('Alimentação'),  'type' => 'expense', 'amount' => 180,   'description' => 'Restaurantes',       'date' => '20'],
                ['category_id' => $cat('Transporte'),   'type' => 'expense', 'amount' => 210,   'description' => 'Combustível',        'date' => '10'],
                ['category_id' => $cat('Contas'),       'type' => 'expense', 'amount' => 1800,  'description' => 'Aluguel',            'date' => '05'],
                ['category_id' => $cat('Contas'),       'type' => 'expense', 'amount' => 140,   'description' => 'Internet + celular', 'date' => '15'],
                ['category_id' => $cat('Lazer'),        'type' => 'expense', 'amount' => 90,    'description' => 'Cinema',             'date' => '22'],
            ],
            4 => [ // Outubro/2025
                ['category_id' => $cat('Salário'),      'type' => 'income',  'amount' => 5250,  'description' => 'Salário',            'date' => '05'],
                ['category_id' => $cat('Alimentação'),  'type' => 'expense', 'amount' => 580,   'description' => 'Supermercado',       'date' => '07'],
                ['category_id' => $cat('Alimentação'),  'type' => 'expense', 'amount' => 220,   'description' => 'Delivery',           'date' => '18'],
                ['category_id' => $cat('Transporte'),   'type' => 'expense', 'amount' => 190,   'description' => 'Combustível',        'date' => '09'],
                ['category_id' => $cat('Contas'),       'type' => 'expense', 'amount' => 1800,  'description' => 'Aluguel',            'date' => '05'],
                ['category_id' => $cat('Contas'),       'type' => 'expense', 'amount' => 140,   'description' => 'Internet + celular', 'date' => '15'],
                ['category_id' => $cat('Roupas'),       'type' => 'expense', 'amount' => 350,   'description' => 'Roupas de frio',     'date' => '25'],
                ['category_id' => $cat('Lazer'),        'type' => 'expense', 'amount' => 420,   'description' => 'Show',               'date' => '19'],
            ],
            3 => [ // Novembro/2025
                ['category_id' => $cat('Salário'),      'type' => 'income',  'amount' => 5250,  'description' => 'Salário',            'date' => '05'],
                ['category_id' => $cat('Freelance'),    'type' => 'income',  'amount' => 1500,  'description' => 'Freela app',         'date' => '20'],
                ['category_id' => $cat('Alimentação'),  'type' => 'expense', 'amount' => 700,   'description' => 'Supermercado',       'date' => '06'],
                ['category_id' => $cat('Alimentação'),  'type' => 'expense', 'amount' => 310,   'description' => 'Churrasquinho',      'date' => '15'],
                ['category_id' => $cat('Transporte'),   'type' => 'expense', 'amount' => 230,   'description' => 'Combustível',        'date' => '10'],
                ['category_id' => $cat('Contas'),       'type' => 'expense', 'amount' => 1800,  'description' => 'Aluguel',            'date' => '05'],
                ['category_id' => $cat('Contas'),       'type' => 'expense', 'amount' => 140,   'description' => 'Internet + celular', 'date' => '15'],
                ['category_id' => $cat('Educação'),     'type' => 'expense', 'amount' => 1250,  'description' => 'Curso de Python',    'date' => '02'],
                ['category_id' => $cat('Lazer'),        'type' => 'expense', 'amount' => 150,   'description' => 'Jantar fora',        'date' => '28'],
            ],
            2 => [ // Dezembro/2025
                ['category_id' => $cat('Salário'),      'type' => 'income',  'amount' => 5250,  'description' => 'Salário',            'date' => '05'],
                ['category_id' => $cat('Salário'),      'type' => 'income',  'amount' => 5250,  'description' => '13º Salário',        'date' => '10'],
                ['category_id' => $cat('Bitcoin'),      'type' => 'income',  'amount' => 500,   'description' => 'Bitcoin venda',      'date' => '22'],
                ['category_id' => $cat('Alimentação'),  'type' => 'expense', 'amount' => 950,   'description' => 'Supermercado natal', 'date' => '20'],
                ['category_id' => $cat('Alimentação'),  'type' => 'expense', 'amount' => 480,   'description' => 'Ceia de natal',      'date' => '24'],
                ['category_id' => $cat('Transporte'),   'type' => 'expense', 'amount' => 280,   'description' => 'Combustível viagem', 'date' => '23'],
                ['category_id' => $cat('Contas'),       'type' => 'expense', 'amount' => 1800,  'description' => 'Aluguel',            'date' => '05'],
                ['category_id' => $cat('Contas'),       'type' => 'expense', 'amount' => 140,   'description' => 'Internet + celular', 'date' => '15'],
                ['category_id' => $cat('Lazer'),        'type' => 'expense', 'amount' => 680,   'description' => 'Viagem réveillon',   'date' => '29'],
                ['category_id' => $cat('Roupas'),       'type' => 'expense', 'amount' => 520,   'description' => 'Presentes natal',    'date' => '18'],
            ],
            1 => [ // Janeiro/2026
                ['category_id' => $cat('Salário'),      'type' => 'income',  'amount' => 5250,  'description' => 'Salário',            'date' => '05'],
                ['category_id' => $cat('Alimentação'),  'type' => 'expense', 'amount' => 640,   'description' => 'Supermercado',       'date' => '08'],
                ['category_id' => $cat('Alimentação'),  'type' => 'expense', 'amount' => 190,   'description' => 'Delivery',           'date' => '20'],
                ['category_id' => $cat('Transporte'),   'type' => 'expense', 'amount' => 200,   'description' => 'Combustível',        'date' => '12'],
                ['category_id' => $cat('Contas'),       'type' => 'expense', 'amount' => 1800,  'description' => 'Aluguel',            'date' => '05'],
                ['category_id' => $cat('Contas'),       'type' => 'expense', 'amount' => 140,   'description' => 'Internet + celular', 'date' => '15'],
                ['category_id' => $cat('Educação'),     'type' => 'expense', 'amount' => 800,   'description' => 'Curso inglês',       'date' => '10'],
                ['category_id' => $cat('Saúde'),        'type' => 'expense', 'amount' => 250,   'description' => 'Consulta médica',    'date' => '14'],
            ],
            0 => [ // Fevereiro/2026 (mês atual)
                ['category_id' => $cat('Salário'),      'type' => 'income',  'amount' => 5250,  'description' => 'Salário',            'date' => '05'],
                ['category_id' => $cat('Freelance'),    'type' => 'income',  'amount' => 1250,  'description' => 'Freela site',        'date' => '15'],
                ['category_id' => $cat('Bitcoin'),      'type' => 'income',  'amount' => 500,   'description' => 'Bitcoin venda',      'date' => '10'],
                ['category_id' => $cat('Lazer'),        'type' => 'expense', 'amount' => 250,   'description' => 'Janta dia dos namorados', 'date' => '14'],
                ['category_id' => $cat('Educação'),     'type' => 'expense', 'amount' => 1250,  'description' => 'Curso de Python',    'date' => '20'],
                ['category_id' => $cat('Alimentação'),  'type' => 'expense', 'amount' => 570,   'description' => 'Supermercado',       'date' => '10'],
                ['category_id' => $cat('Transporte'),   'type' => 'expense', 'amount' => 180,   'description' => 'Combustível',        'date' => '08'],
                ['category_id' => $cat('Contas'),       'type' => 'expense', 'amount' => 1800,  'description' => 'Aluguel',            'date' => '05'],
                ['category_id' => $cat('Contas'),       'type' => 'expense', 'amount' => 140,   'description' => 'Internet + celular', 'date' => '15'],
            ],
        ];

        foreach ($months as $monthsAgo => $transactions) {
            $base = now()->subMonths($monthsAgo);

            foreach ($transactions as $transaction) {
                $date = Carbon::create($base->year, $base->month, (int) $transaction['date']);

                Transaction::firstOrCreate(
                    [
                        'category_id' => $transaction['category_id'],
                        'description' => $transaction['description'],
                        'date'        => $date->toDateString(),
                    ],
                    [
                        'type'   => $transaction['type'],
                        'amount' => $transaction['amount'],
                        'notes'  => null,
                    ]
                );
            }
        }
    }
}
