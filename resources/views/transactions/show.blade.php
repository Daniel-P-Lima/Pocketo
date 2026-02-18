@extends('layouts.app')

@php $header = 'Detalhes'; $backUrl = route('transactions.index'); @endphp

@section('content')
<div class="px-4 py-4 space-y-4">
    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-700 text-sm px-4 py-3 rounded-xl">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm p-5 space-y-4">
        {{-- Amount --}}
        <div class="text-center">
            <p class="text-3xl font-bold {{ $transaction->type === 'income' ? 'text-emerald-600' : 'text-red-500' }}">
                {{ $transaction->type === 'income' ? '+' : '-' }} {{ $transaction->formatted_amount }}
            </p>
            <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-medium
                         {{ $transaction->type === 'income' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                {{ $transaction->type === 'income' ? 'Receita' : 'Despesa' }}
            </span>
        </div>

        <hr class="border-gray-100">

        {{-- Details --}}
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500">Descricao</span>
                <span class="text-sm font-medium text-gray-900">{{ $transaction->description }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500">Categoria</span>
                <span class="text-sm font-medium text-gray-900">
                    {{ $transaction->category->icon }} {{ $transaction->category->name }}
                </span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500">Data</span>
                <span class="text-sm font-medium text-gray-900">{{ $transaction->date->format('d/m/Y') }}</span>
            </div>
            @if($transaction->notes)
                <div>
                    <span class="text-sm text-gray-500">Observacoes</span>
                    <p class="text-sm text-gray-700 mt-1">{{ $transaction->notes }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Actions --}}
    <div class="space-y-2">
        <a href="{{ route('transactions.edit', $transaction) }}"
           class="block w-full text-center bg-blue-600 text-white font-semibold py-3.5 rounded-xl active:bg-blue-700">
            Editar
        </a>

        <form method="POST" action="{{ route('transactions.destroy', $transaction) }}"
              onsubmit="return confirm('Tem certeza que deseja excluir esta transacao?')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="w-full text-red-600 font-medium py-3 rounded-xl border border-red-200 active:bg-red-50">
                Excluir
            </button>
        </form>
    </div>
</div>
@endsection
