@extends('layouts.app')

@php $header = 'Editar Orcamento'; $backUrl = route('budgets.index', ['month' => $budget->month, 'year' => $budget->year]); @endphp

@section('content')
<form method="POST" action="{{ route('budgets.update', $budget) }}" class="px-4 py-4 space-y-5">
    @csrf
    @method('PUT')

    {{-- Category (read-only) --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
        <div class="flex items-center gap-3 bg-gray-100 rounded-xl px-4 py-3">
            <span class="text-2xl">{{ $budget->category->icon }}</span>
            <span class="text-sm font-medium text-gray-800">{{ $budget->category->name }}</span>
        </div>
    </div>

    {{-- Current status --}}
    <div class="bg-white rounded-xl p-4 shadow-sm space-y-2">
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Gasto atual</span>
            <span class="font-semibold {{ $budget->spent_percentage >= 80 ? 'text-red-500' : 'text-gray-900' }}">
                @brl($budget->spent)
            </span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            @php
                $pct = $budget->spent_percentage;
                $barColor = $pct >= 80 ? 'bg-red-500' : ($pct >= 60 ? 'bg-amber-500' : 'bg-emerald-500');
            @endphp
            <div class="{{ $barColor }} h-2 rounded-full" style="width: {{ min($pct, 100) }}%"></div>
        </div>
    </div>

    {{-- Amount --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Limite mensal</label>
        <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">R$</span>
            <input type="text" inputmode="numeric" data-currency="amount-hidden"
                   class="w-full bg-white border border-gray-300 rounded-xl pl-10 pr-4 py-3 text-lg font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   placeholder="0,00">
            <input type="hidden" name="amount" id="amount-hidden" value="{{ old('amount', $budget->amount) }}">
        </div>
        @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <button type="submit"
            class="w-full bg-blue-600 text-white font-semibold py-3.5 rounded-xl active:bg-blue-700">
        Atualizar
    </button>
</form>

<form method="POST" action="{{ route('budgets.destroy', $budget) }}" class="px-4 pb-4"
      onsubmit="return confirm('Tem certeza que deseja excluir este orcamento?')">
    @csrf
    @method('DELETE')
    <button type="submit"
            class="w-full text-red-600 font-medium py-3 rounded-xl border border-red-200 active:bg-red-50">
        Excluir Orcamento
    </button>
</form>
@endsection
