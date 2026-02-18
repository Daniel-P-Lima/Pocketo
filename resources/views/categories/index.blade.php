@extends('layouts.app')

@php $header = 'Categorias'; @endphp

@section('content')
<div class="px-4 py-4 space-y-6">
    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-700 text-sm px-4 py-3 rounded-xl">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 text-red-700 text-sm px-4 py-3 rounded-xl">{{ session('error') }}</div>
    @endif

    {{-- Expense categories --}}
    <div>
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2 px-1">Despesas</h2>
        <div class="space-y-2">
            @foreach($expenseCategories as $category)
                <a href="{{ route('categories.edit', $category) }}"
                   class="flex items-center gap-3 bg-white rounded-xl px-4 py-3 shadow-sm active:bg-gray-50">
                    <span class="text-2xl">{{ $category->icon }}</span>
                    <span class="flex-1 text-sm font-medium text-gray-800">{{ $category->name }}</span>
                    <span class="w-3 h-3 rounded-full" style="background-color: {{ $category->color }}"></span>
                    @if($category->is_default)
                        <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    {{-- Income categories --}}
    <div>
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2 px-1">Receitas</h2>
        <div class="space-y-2">
            @foreach($incomeCategories as $category)
                <a href="{{ route('categories.edit', $category) }}"
                   class="flex items-center gap-3 bg-white rounded-xl px-4 py-3 shadow-sm active:bg-gray-50">
                    <span class="text-2xl">{{ $category->icon }}</span>
                    <span class="flex-1 text-sm font-medium text-gray-800">{{ $category->name }}</span>
                    <span class="w-3 h-3 rounded-full" style="background-color: {{ $category->color }}"></span>
                    @if($category->is_default)
                        <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>

{{-- FAB --}}
<a href="{{ route('categories.create') }}"
   class="fixed bottom-20 right-4 w-14 h-14 bg-blue-600 text-white rounded-full shadow-lg flex items-center justify-center active:bg-blue-700 z-40">
    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
</a>
@endsection
