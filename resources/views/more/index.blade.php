@extends('layouts.app')

@php $header = 'Mais'; @endphp

@section('content')
<div class="px-4 py-4 space-y-3">
    <a href="{{ route('categories.index') }}"
       class="flex items-center gap-4 bg-white rounded-xl px-4 py-4 shadow-sm active:bg-gray-50">
        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
        </div>
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-900">Categorias</p>
            <p class="text-xs text-gray-500">Gerenciar categorias de receita e despesa</p>
        </div>
        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>

    {{-- App info --}}
    <div class="bg-white rounded-xl px-4 py-4 shadow-sm mt-6">
        <div class="text-center space-y-1">
            <p class="text-lg font-bold text-gray-800">Financas</p>
            <p class="text-xs text-gray-500">Versao 1.0.0</p>
            <p class="text-xs text-gray-400 mt-2">Feito com Laravel + NativePHP</p>
        </div>
    </div>
</div>
@endsection
