<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Icon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    private array $types = ['expense', 'income', 'both'];

    private function formOptions(): array
    {
        return [
            'icons'  => Icon::all(),
            'colors' => Category::pluck('color')->unique()->values(),
            'types'  => $this->types,
        ];
    }

    public function index()
    {
        $expenseCategories = Category::expense()->orderBy('name')->get();
        $incomeCategories = Category::income()->orderBy('name')->get();

        return Inertia::render('Categories/Index', [
            'header'            => 'Categorias',
            'expenseCategories' => $expenseCategories,
            'incomeCategories'  => $incomeCategories,
        ]);
    }

    public function create()
    {
        return Inertia::render('Categories/Create', [
            'header'  => 'Nova Categoria',
            'backUrl' => route('categories.index'),
            ...$this->formOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'icon_id' => 'required|exists:icons,id',
            'color'   => 'required|string|max:7',
            'type'    => 'required|in:income,expense,both',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Categoria criada!');
    }

    public function edit(Category $category)
    {
        $options = $this->formOptions();

        if (! $options['colors']->contains($category->color)) {
            $options['colors']->prepend($category->color);
        }

        return Inertia::render('Categories/Edit', [
            'header'    => 'Editar Categoria',
            'backUrl'   => route('categories.index'),
            'category'  => $category,
            'canDelete' => ! $category->is_default && $category->transactions()->doesntExist(),
            ...$options,
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'icon_id' => 'required|exists:icons,id',
            'color'   => 'required|string|max:7',
            'type'    => 'required|in:income,expense,both',
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