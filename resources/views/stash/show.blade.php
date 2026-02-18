@extends('layouts.app')

@php $header = 'Detalhes ' . $stash->name ; $backUrl = route('stash.index'); @endphp

@section('content')
<div class="px-4 py-4 space-y-4">
    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-700 text-sm px-4 py-3 rounded-xl">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm p-5 space-y-4">
        <div class="text-center">
            <p class="text-3xl font-bold">
                {{ $stash->amount }}
            </p>
            <p class="text-3xl font-bold">
                {{ $stash->goal_amount }}
            </p>
        </div>
        {{-- Details --}}
        <div class="space-y-3">
            @if($stash->purpose)
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Observações</span>
                    <span class="text-sm font-medium text-gray-900">
                        {{ $stash->purpose}}
                    </span>
                </div>
            @endif

            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500">Data</span>
                <span class="text-sm font-medium text-gray-900">{{ $stash->created_at->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection