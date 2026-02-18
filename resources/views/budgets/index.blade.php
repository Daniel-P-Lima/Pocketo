@extends('layouts.app')

@php $header = 'Orçamentos'; @endphp

@section('content')
<div class="px-4 py-4 space-y-4">
    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-700 text-sm px-4 py-3 rounded-xl">{{ session('success') }}</div>
    @endif

    <x-month-picker :month="$month" :year="$year" route="budgets.index" />

    {{-- Summary --}}
    @if($budgets->isNotEmpty())
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm text-gray-500">Total Orçado</span>
                <span class="text-sm font-bold text-gray-900">@brl($totalBudgeted)</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500">Total Gasto</span>
                <span class="text-sm font-bold {{ $totalSpent > $totalBudgeted ? 'text-red-500' : 'text-emerald-600' }}">
                    @brl($totalSpent)
                </span>
            </div>
        </div>
    @endif

    {{-- Budget list --}}
    @if($budgets->isEmpty())
        <x-empty-state message="Nenhum orcamento definido para este mes."
                       :actionUrl="route('budgets.create', ['month' => $month, 'year' => $year])"
                       actionLabel="Definir Orcamento" />
    @else
        <div class="space-y-2">
            @foreach($budgets as $budget)
                <x-budget-progress :budget="$budget" />
            @endforeach
        </div>
    @endif
</div>

{{-- FAB --}}
<a href="{{ route('budgets.create', ['month' => $month, 'year' => $year]) }}"
   class="fixed bottom-20 right-4 w-14 h-14 bg-blue-600 text-white rounded-full shadow-lg flex items-center justify-center active:bg-blue-700 z-40">
    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
</a>
@endsection
