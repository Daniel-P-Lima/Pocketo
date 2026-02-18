@extends('layouts.app')

@php $header = 'Editar Categoria'; $backUrl = route('categories.index'); @endphp

@section('content')
<form method="POST" action="{{ route('categories.update', $category) }}" class="px-4 py-4 space-y-5">
    @csrf
    @method('PUT')

    {{-- Name --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
        <input type="text" name="name" value="{{ old('name', $category->name) }}" required maxlength="100"
               class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Type --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
        <div class="flex gap-2">
            @foreach(['expense' => 'Despesa', 'income' => 'Receita', 'both' => 'Ambos'] as $value => $label)
                <label class="flex-1">
                    <input type="radio" name="type" value="{{ $value }}" class="peer hidden"
                           {{ old('type', $category->type) === $value ? 'checked' : '' }}>
                    <div class="text-center py-2.5 rounded-xl border border-gray-300 text-sm font-medium cursor-pointer
                                peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600
                                active:bg-gray-100">
                        {{ $label }}
                    </div>
                </label>
            @endforeach
        </div>
    </div>

    {{-- Icon --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Icone</label>
        @php
            $icons = ['🍔','🚗','🏠','❤️','📚','🎮','👕','📄','📦','💼','💻','📈','🛒','✈️','🎵','⚽','💊','🐕','🎁','💡'];
        @endphp
        <div class="grid grid-cols-5 gap-2">
            @foreach($icons as $icon)
                <label class="cursor-pointer">
                    <input type="radio" name="icon" value="{{ $icon }}" class="peer hidden"
                           {{ old('icon', $category->icon) === $icon ? 'checked' : '' }}>
                    <div class="flex items-center justify-center h-12 rounded-xl border border-gray-200 text-2xl
                                peer-checked:ring-2 peer-checked:ring-blue-500 peer-checked:border-blue-500
                                active:bg-gray-100">
                        {{ $icon }}
                    </div>
                </label>
            @endforeach
        </div>
    </div>

    {{-- Color --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Cor</label>
        @php
            $colors = ['#EF4444','#F59E0B','#10B981','#3B82F6','#6366F1','#8B5CF6','#EC4899','#06B6D4','#64748B','#EAB308'];
        @endphp
        <div class="flex gap-2 flex-wrap">
            @foreach($colors as $color)
                <label class="cursor-pointer">
                    <input type="radio" name="color" value="{{ $color }}" class="peer hidden"
                           {{ old('color', $category->color) === $color ? 'checked' : '' }}>
                    <div class="w-10 h-10 rounded-full border-2 border-transparent peer-checked:border-gray-800 peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-gray-400"
                         style="background-color: {{ $color }}"></div>
                </label>
            @endforeach
        </div>
    </div>

    <button type="submit"
            class="w-full bg-blue-600 text-white font-semibold py-3.5 rounded-xl active:bg-blue-700">
        Atualizar
    </button>
</form>

@if(!$category->is_default && $category->transactions()->doesntExist())
    <form method="POST" action="{{ route('categories.destroy', $category) }}" class="px-4 pb-4"
          onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?')">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="w-full text-red-600 font-medium py-3 rounded-xl border border-red-200 active:bg-red-50">
            Excluir Categoria
        </button>
    </form>
@endif
@endsection
