<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Category;
use App\Models\Transaction;
use App\Services\NubankCsvParser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use InvalidArgumentException;

class TransactionImportController extends Controller
{
    private const SESSION_KEY = 'transaction_import';

    // Pagamento da própria fatura: não é receita, é só a quitação do cartão
    private const SKIPPED_BY_DEFAULT = ['Pagamento recebido'];

    public function create()
    {
        return Inertia::render('Transactions/Import', [
            'header' => 'Importar CSV',
            'backUrl' => route('transactions.index'),
            'banks' => Bank::orderBy('name')->get(),
            'defaultBankId' => Bank::where('name', 'Nubank')->value('id'),
        ]);
    }

    public function upload(Request $request, NubankCsvParser $parser)
    {
        if (! $request->filled('reference_month') && $request->hasFile('file')) {
            $request->merge([
                'reference_month' => $parser->guessReferenceMonth($request->file('file')->getClientOriginalName()),
            ]);
        }

        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
            'bank_id' => 'nullable|exists:banks,id',
            'reference_month' => 'required|date_format:Y-m',
        ], [
            'reference_month.required' => 'Informe o mês de vencimento da fatura.',
        ]);

        try {
            $rows = $parser->parse($request->file('file')->getRealPath());
        } catch (InvalidArgumentException $e) {
            throw ValidationException::withMessages(['file' => $e->getMessage()]);
        }

        // Todas as compras da fatura contam no mês em que ela vence
        $extra = [
            'bank_id' => $request->integer('bank_id') ?: null,
            'reference_month' => $request->input('reference_month'),
        ];
        $rows = array_map(fn ($row) => [...$row, ...$extra], $rows);

        $request->session()->put(self::SESSION_KEY, $rows);

        return redirect()->route('transactions.import.preview');
    }

    public function preview(Request $request)
    {
        $rows = $request->session()->get(self::SESSION_KEY);

        if (! $rows) {
            return redirect()->route('transactions.import.create');
        }

        $expenseCategories = Category::expense()->orderBy('name')->get();
        $incomeCategories = Category::income()->orderBy('name')->get();
        $fallbackCategory = Category::where('type', 'both')->where('name', 'Outros')->value('id');

        return Inertia::render('Transactions/ImportPreview', [
            'header' => 'Revisar importação',
            'backUrl' => route('transactions.import.create'),
            'rows' => $this->annotate($rows, $fallbackCategory),
            'expenseCategories' => $expenseCategories,
            'incomeCategories' => $incomeCategories,
            'bank' => Bank::find($rows[0]['bank_id'] ?? null),
            'referenceMonth' => $rows[0]['reference_month'] ?? null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rows' => 'required|array|min:1',
            'rows.*.date' => 'required|date',
            'rows.*.description' => 'required|string|max:255',
            'rows.*.amount' => 'required|numeric|min:0.01',
            'rows.*.type' => 'required|in:income,expense',
            'rows.*.category_id' => 'required|exists:categories,id',
            'rows.*.bank_id' => 'nullable|exists:banks,id',
            'rows.*.reference_month' => 'required|date_format:Y-m',
        ], [
            'rows.*.category_id.required' => 'Selecione a categoria de todas as transações.',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['rows'] as $row) {
                Transaction::create($row);
            }
        });

        $request->session()->forget(self::SESSION_KEY);

        $date = Carbon::parse(collect($validated['rows'])->max('reference_month') . '-01');
        $count = count($validated['rows']);

        return redirect()
            ->route('transactions.index', ['month' => $date->month, 'year' => $date->year])
            ->with('success', "{$count} transações importadas!");
    }

    /**
     * Sugere a categoria a partir do último lançamento com a mesma descrição
     * e marca as linhas que provavelmente já foram lançadas.
     */
    private function annotate(array $rows, ?int $fallbackCategory): array
    {
        $rows = collect($rows);

        $lastCategories = Transaction::whereIn('description', $rows->pluck('description')->unique())
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get(['description', 'type', 'category_id'])
            ->unique(fn ($t) => $t->type . '|' . $t->description)
            ->mapWithKeys(fn ($t) => [$t->type . '|' . $t->description => $t->category_id]);

        // Chave "data|descrição|valor" -> quantas já existem no banco
        $existing = Transaction::whereBetween('date', [$rows->min('date'), $rows->max('date')])
            ->get(['date', 'description', 'amount'])
            ->countBy(fn ($t) => $t->date->toDateString() . '|' . $t->description . '|' . $t->amount)
            ->all();

        return $rows->values()->map(function ($row, $index) use ($lastCategories, $fallbackCategory, &$existing) {
            $key = $row['date'] . '|' . $row['description'] . '|' . number_format($row['amount'], 2, '.', '');

            // Compras repetidas no mesmo dia (ex: duas no mesmo bar) só contam como duplicadas uma a uma
            $duplicate = ($existing[$key] ?? 0) > 0;
            if ($duplicate) {
                $existing[$key]--;
            }

            return [
                ...$row,
                'id' => $index,
                'category_id' => $lastCategories[$row['type'] . '|' . $row['description']] ?? $fallbackCategory,
                'duplicate' => $duplicate,
                'selected' => ! $duplicate && ! in_array($row['description'], self::SKIPPED_BY_DEFAULT),
            ];
        })->all();
    }
}
