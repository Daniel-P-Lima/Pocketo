<?php

namespace App\Http\Controllers;

use App\Models\Stash;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StashController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stashes = Stash::all();
        return Inertia::render('Stash/Index', [
            'header' => "Caixinhas",
            'stashes' => $stashes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Stash/Create', [
            'header' => 'Nova Caixinha',
            'backUrl' => route('stash.index'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|decimal:2|min:1',
            'goal_amount' => 'required|decimal:2|min:1',
            'purpose' => 'required|string|max:255',
        ]);

        Stash::create($validated);

        return redirect()->route('stash.index')->with('success', 'Transacao adicionada!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Stash $stash)
    {
        return Inertia::render('Stash/Show', [
            'stash' => $stash,
            'header' => 'Detalhes caixinha: ' . $stash->name,
            'backUrl' => route('stash.index'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stash $stash)
    {
        return Inertia::render('Stash/Edit', [
            'stash' => $stash,
            'header' => 'Editar caixinha: ' . $stash->name,
            'backUrl' => route('stash.show', $stash),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stash $stash)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'goal_amount' => 'required|numeric|min:1',
            'purpose' => 'required|string|max:255',
        ]);

        $stash->update($validated);

        return redirect()->route('stash.show', $stash)->with('success', 'Caixinha atualizada!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stash $stash)
    {
        $stash->delete();

        return redirect()->route('stash.index')->with('success', 'Caixinha excluída!');
    }
}
