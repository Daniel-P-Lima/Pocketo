@props(['budget'])

@php
    $pct = $budget->spent_percentage;
    $barColor = $pct >= 80 ? 'bg-red-500' : ($pct >= 60 ? 'bg-amber-500' : 'bg-emerald-500');
    $textColor = $pct >= 80 ? 'text-red-600' : ($pct >= 60 ? 'text-amber-600' : 'text-emerald-600');
@endphp

<div class="bg-white rounded-xl px-4 py-3 shadow-sm">
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center gap-2">
            <span class="text-lg">{{ $budget->category->icon }}</span>
            <span class="text-sm font-medium text-gray-800">{{ $budget->category->name }}</span>
        </div>
        <a href="{{ route('budgets.edit', $budget) }}" class="text-gray-400 p-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
        </a>
    </div>

    <div class="w-full bg-gray-200 rounded-full h-2 mb-1.5">
        <div class="{{ $barColor }} h-2 rounded-full transition-all duration-300"
             style="width: {{ min($pct, 100) }}%"></div>
    </div>

    <div class="flex justify-between text-xs">
        <span class="{{ $textColor }} font-medium">@brl($budget->spent) de @brl($budget->amount)</span>
        <span class="text-gray-500">{{ $pct }}%</span>
    </div>
</div>
