<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class BankController extends Controller
{
    public function index()
    {
        return Inertia::render('Banks/Index', [
            'header'  => 'Bancos',
            'backUrl' => route('more'),
            'banks'   => Bank::withCount('transactions')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Banks/Create', [
            'header'  => 'Novo Banco',
            'backUrl' => route('banks.index'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:100|unique:banks,name',
            'color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        Bank::create($validated);

        return redirect()->route('banks.index')->with('success', 'Banco criado!');
    }

    public function edit(Bank $bank)
    {
        return Inertia::render('Banks/Edit', [
            'header'    => 'Editar Banco',
            'backUrl'   => route('banks.index'),
            'bank'      => $bank,
            'canDelete' => $bank->transactions()->doesntExist(),
        ]);
    }

    public function update(Request $request, Bank $bank)
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:100', Rule::unique('banks', 'name')->ignore($bank)],
            'color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        $bank->update($validated);

        return redirect()->route('banks.index')->with('success', 'Banco atualizado!');
    }

    public function destroy(Bank $bank)
    {
        if ($bank->transactions()->exists()) {
            return back()->with('error', 'Banco possui transacoes e nao pode ser excluido.');
        }

        $bank->delete();

        return redirect()->route('banks.index')->with('success', 'Banco excluido!');
    }
}
