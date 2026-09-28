<?php

namespace App\Http\Controllers;

use App\Helpers\Currency;
use App\Models\Bank;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);
        $type = $request->get('type');
        $bankId = $request->integer('bank') ?: null;

        $query = Transaction::with(['category', 'bank'])
            ->inMonth($month, $year)
            ->fromBank($bankId)
            ->orderByDesc('date')
            ->orderByDesc('id');

        if ($type && in_array($type, ['income', 'expense'])) {
            $query->where('type', $type);
        }

        $transactions = $query->get();

        $totalIncome = Transaction::inMonth($month, $year)->fromBank($bankId)->income()->sum('amount');
        $totalExpense = Transaction::inMonth($month, $year)->fromBank($bankId)->expense()->sum('amount');

        return Inertia::render('Transactions/Index',[
            'header' => "Transações",
            'transactions' => $transactions,
            'month' => $month,
            'year' => $year,
            'type' => $type,
            'bank' => $bankId,
            'banks' => Bank::orderBy('name')->get(),
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense
        ]);
    }

    public function create()
    {
        $expenseCategories = Category::expense()->orderBy('name')->get();
        $incomeCategories = Category::income()->orderBy('name')->get();

        return Inertia::render('Transactions/Create', [
            'header' => 'Nova Transacao',
            'backUrl' => route('transactions.index'),
            'expenseCategories' => $expenseCategories,
            'incomeCategories' => $incomeCategories,
            'banks' => Bank::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'bank_id' => 'nullable|exists:banks,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'date' => 'required|date',
            'reference_month' => 'nullable|date_format:Y-m',
        ]);

        Transaction::create($validated);

        return redirect()->route('transactions.index')->with('success', 'Transacao adicionada!');
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['category', 'bank']);

        return Inertia::render('Transactions/Show', [
            'header' => $transaction->description,
            'backUrl' => route('transactions.index'),
            'transaction' => $transaction,
        ]);
    }

    public function edit(Transaction $transaction)
    {
        $expenseCategories = Category::expense()->orderBy('name')->get();
        $incomeCategories = Category::income()->orderBy('name')->get();

        return Inertia::render('Transactions/Edit', [
            'header'            => "Transação",
            'backUrl'           => route('transactions.index'),
            'transaction'       => $transaction,
            'expenseCategories' => $expenseCategories,
            'incomeCategories'  => $incomeCategories,
            'banks'             => Bank::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'bank_id' => 'nullable|exists:banks,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'date' => 'required|date',
            'reference_month' => 'nullable|date_format:Y-m',
        ]);

        // Sem mês informado, volta a seguir a data (o model preenche ao salvar)
        $validated['reference_month'] ??= null;

        $transaction->update($validated);

        return redirect()->route('transactions.show', $transaction)->with('success', 'Transacao atualizada!');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transacao excluida!');
    }
}
