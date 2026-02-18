@props(['month', 'year', 'route'])

@php
    $months = ['Janeiro','Fevereiro','Marco','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
    $prevMonth = $month == 1 ? 12 : $month - 1;
    $prevYear = $month == 1 ? $year - 1 : $year;
    $nextMonth = $month == 12 ? 1 : $month + 1;
    $nextYear = $month == 12 ? $year + 1 : $year;
@endphp

<div class="flex items-center justify-between bg-white rounded-xl px-4 py-3 shadow-sm">
    <a href="{{ route($route, ['month' => $prevMonth, 'year' => $prevYear]) }}"
       class="p-2 rounded-full hover:bg-gray-100 active:bg-gray-200">
        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>

    <span class="text-base font-semibold text-gray-800">
        {{ $months[$month - 1] }} {{ $year }}
    </span>

    <a href="{{ route($route, ['month' => $nextMonth, 'year' => $nextYear]) }}"
       class="p-2 rounded-full hover:bg-gray-100 active:bg-gray-200">
        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
</div>
