<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $expenseCategories = Category::expense()->orderBy('name')->get();
        $incomeCategories = Category::income()->orderBy('name')->get();

        return view('categories.index', compact('expenseCategories', 'incomeCategories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'icon' => 'required|string|max:50',
            'color' => 'required|string|max:7',
            'type' => 'required|in:income,expense,both',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Categoria criada!');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'icon' => 'required|string|max:50',
            'color' => 'required|string|max:7',
            'type' => 'required|in:income,expense,both',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Categoria atualizada!');
    }

    public function destroy(Category $category)
    {
        if ($category->is_default) {
            return back()->with('error', 'Categorias padrao nao podem ser excluidas.');
        }

        if ($category->transactions()->exists()) {
            return back()->with('error', 'Categoria possui transacoes e nao pode ser excluida.');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Categoria excluida!');
    }
}
