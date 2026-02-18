@extends('layouts.app')

@php $header = 'Novo Orcamento'; $backUrl = route('budgets.index', ['month' => $month, 'year' => $year]); @endphp

@section('content')
<form method="POST" action="{{ route('budgets.store') }}" class="px-4 py-4 space-y-5">
    @csrf
    <input type="hidden" name="month" value="{{ $month }}">
    <input type="hidden" name="year" value="{{ $year }}">

    {{-- Category --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
        @if($categories->isEmpty())
            <p class="text-sm text-gray-500 bg-gray-100 rounded-xl p-4 text-center">
                Todas as categorias ja possuem orcamento neste mes.
            </p>
        @else
            <div class="grid grid-cols-3 gap-2">
                @foreach($categories as $cat)
                    <label class="cursor-pointer">
                        <input type="radio" name="category_id" value="{{ $cat->id }}" class="peer hidden"
                               {{ old('category_id') == $cat->id ? 'checked' : '' }}>
                        <div class="flex flex-col items-center gap-1 py-3 rounded-xl border border-gray-200 text-xs
                                    peer-checked:ring-2 peer-checked:ring-blue-500 peer-checked:border-blue-500 active:bg-gray-50">
                            <span class="text-xl">{{ $cat->icon }}</span>
                            <span class="text-gray-600 font-medium">{{ $cat->name }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
        @endif
        @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Amount --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Limite mensal</label>
        <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">R$</span>
            <input type="text" inputmode="numeric" data-currency="amount-hidden"
                   class="w-full bg-white border border-gray-300 rounded-xl pl-10 pr-4 py-3 text-lg font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   placeholder="0,00">
            <input type="hidden" name="amount" id="amount-hidden" value="{{ old('amount', '') }}">
        </div>
        @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    @if($categories->isNotEmpty())
        <button type="submit"
                class="w-full bg-blue-600 text-white font-semibold py-3.5 rounded-xl active:bg-blue-700">
            Salvar
        </button>
    @endif
</form>
@endsection
