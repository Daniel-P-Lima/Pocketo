@extends('layouts.app')

@php $header = 'Nova Transacao'; $backUrl = route('transactions.index'); @endphp

@section('content')
<form method="POST" action="{{ route('transactions.store') }}" class="px-4 py-4 space-y-5">
    @csrf

    {{-- Type toggle --}}
    <div>
        <div class="flex gap-2">
            <label class="flex-1">
                <input type="radio" name="type" value="expense" class="peer hidden" id="type-expense"
                       {{ old('type', 'expense') === 'expense' ? 'checked' : '' }}>
                <div class="text-center py-3 rounded-xl border border-gray-300 text-sm font-semibold cursor-pointer
                            peer-checked:bg-red-500 peer-checked:text-white peer-checked:border-red-500
                            active:bg-gray-100">
                    Despesa
                </div>
            </label>
            <label class="flex-1">
                <input type="radio" name="type" value="income" class="peer hidden" id="type-income"
                       {{ old('type') === 'income' ? 'checked' : '' }}>
                <div class="text-center py-3 rounded-xl border border-gray-300 text-sm font-semibold cursor-pointer
                            peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-500
                            active:bg-gray-100">
                    Receita
                </div>
            </label>
        </div>
    </div>

    {{-- Amount --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Valor</label>
        <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">R$</span>
            <input type="text" inputmode="numeric" data-currency="amount-hidden"
                   class="w-full bg-white border border-gray-300 rounded-xl pl-10 pr-4 py-3 text-lg font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   placeholder="0,00" value="{{ old('amount') ? number_format(old('amount') / 100, 2, ',', '.') : '' }}">
            <input type="hidden" name="amount" id="amount-hidden" value="{{ old('amount', '') }}">
        </div>
        @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Description --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Descricao</label>
        <input type="text" name="description" value="{{ old('description') }}" required maxlength="255"
               class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
               placeholder="Ex: Supermercado">
        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Category --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>

        {{-- Expense categories --}}
        <div id="expense-categories">
            <div class="grid grid-cols-4 gap-2">
                @foreach($expenseCategories as $cat)
                    <label class="cursor-pointer">
                        <input type="radio" name="category_id" value="{{ $cat->id }}" class="peer hidden"
                               {{ old('category_id') == $cat->id ? 'checked' : '' }}>
                        <div class="flex flex-col items-center gap-1 py-2.5 rounded-xl border border-gray-200 text-xs
                                    peer-checked:ring-2 peer-checked:ring-blue-500 peer-checked:border-blue-500 active:bg-gray-50">
                            <span class="text-xl">{{ $cat->icon }}</span>
                            <span class="text-gray-600 truncate w-full text-center px-1">{{ $cat->name }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Income categories --}}
        <div id="income-categories" class="hidden">
            <div class="grid grid-cols-4 gap-2">
                @foreach($incomeCategories as $cat)
                    <label class="cursor-pointer">
                        <input type="radio" name="category_id" value="{{ $cat->id }}" class="peer hidden"
                               {{ old('category_id') == $cat->id ? 'checked' : '' }}>
                        <div class="flex flex-col items-center gap-1 py-2.5 rounded-xl border border-gray-200 text-xs
                                    peer-checked:ring-2 peer-checked:ring-blue-500 peer-checked:border-blue-500 active:bg-gray-50">
                            <span class="text-xl">{{ $cat->icon }}</span>
                            <span class="text-gray-600 truncate w-full text-center px-1">{{ $cat->name }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
        @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Date --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Data</label>
        <input type="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required
               class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Notes --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Observacoes (opcional)</label>
        <textarea name="notes" rows="2" maxlength="1000"
                  class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                  placeholder="Detalhes adicionais...">{{ old('notes') }}</textarea>
    </div>

    <button type="submit"
            class="w-full bg-blue-600 text-white font-semibold py-3.5 rounded-xl active:bg-blue-700">
        Salvar
    </button>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const typeInputs = document.querySelectorAll('input[name="type"]');
    const expenseDiv = document.getElementById('expense-categories');
    const incomeDiv = document.getElementById('income-categories');

    function toggleCategories() {
        const selected = document.querySelector('input[name="type"]:checked')?.value;
        expenseDiv.classList.toggle('hidden', selected !== 'expense');
        incomeDiv.classList.toggle('hidden', selected !== 'income');
    }

    typeInputs.forEach(input => input.addEventListener('change', toggleCategories));
    toggleCategories();
});
</script>
@endpush
@endsection
