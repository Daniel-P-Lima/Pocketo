@extends('layouts.app')

@php $header = 'Nova caixinha'; @endphp

@section('content')
    <div class="px-4 py-4 space-y-4">

        {{-- Form --}}
        <form method="POST" action="{{ route('stash.store') }}" class="space-y-4 max-w-md flex flex-col place-self-center">
            @csrf

            {{-- Nome --}}
            <div class="space-y-1">
                <label for="name" class="text-sm font-medium text-gray-900">Nome</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name') }}"
                    maxlength="60"
                    required
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900
                        placeholder:text-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#B4A5D9]"
                    placeholder="Ex.: Reserva de emergência"
                >
                @error('name')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Valor inicial --}}
            <div class="space-y-1">
                <label for="amount" class="text-sm font-medium text-gray-900">Valor inicial (R$)</label>
                <input
                    id="amount"
                    name="amount"
                    type="number"
                    value="{{ old('amount', 0) }}"
                    min="0"
                    step="1"
                    required
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900
                        placeholder:text-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#B4A5D9]"
                    placeholder="0"
                >
                @error('amount')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            
            {{-- Objetivo --}}
            <div class="space-y-1">
                <label for="amount" class="text-sm font-medium text-gray-900">Objetivo (R$)</label>
                <input
                    id="goal_amount"
                    name="goal_amount"
                    type="number"
                    value="{{ old('goal_amount', 0) }}"
                    min="0"
                    step="1"
                    required
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900
                        placeholder:text-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#B4A5D9]"
                    placeholder="0"
                >
                @error('goal_amount')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Observações --}}
            <div class="space-y-1">
                <label for="purpose" class="text-sm font-medium text-gray-900">Observações</label>
                <textarea
                    id="purpose"
                    name="purpose"
                    rows="3"
                    maxlength="255"
                    required
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900
                        placeholder:text-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#B4A5D9]"
                    placeholder="Ex.: Quando houver algum problema"
                >{{ old('purpose') }}</textarea>
                @error('purpose')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Ações --}}
            <div class="flex items-center gap-3 pt-2">
                <button
                    type="submit"
                    class="flex-1 rounded-xl bg-[#3F2D56] text-white px-4 py-3 text-sm font-semibold shadow-sm
                        active:scale-[0.99] hover:bg-[#342447]">
                    Criar caixinha
                </button>

                <a href="{{ route('stash.index') }}"
                class="flex-1 rounded-xl bg-white text-gray-900 px-4 py-3 text-sm font-semibold shadow-sm
                        border border-gray-200 text-center active:scale-[0.99] hover:bg-gray-50">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

{{-- Preview ao vivo (sem frameworks) --}}
<script>
    (function () {
    const nameEl = document.getElementById('name');
    const amountEl = document.getElementById('amount');
    const purposeEl = document.getElementById('purpose');

    const previewName = document.getElementById('previewName');
    const previewAmount = document.getElementById('previewAmount');
    const previewPurpose = document.getElementById('previewPurpose');

    const formatBRL = (value) => {
        const n = Number(value || 0);
        return n.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    };

    const sync = () => {
        previewName.textContent = nameEl.value.trim() || 'Nome da caixinha';
        previewAmount.textContent = formatBRL(amountEl.value);
        previewPurpose.textContent = purposeEl.value.trim() || 'Objetivo da caixinha…';
    };

    ['input','change'].forEach(evt => {
        nameEl?.addEventListener(evt, sync);
        amountEl?.addEventListener(evt, sync);
        purposeEl?.addEventListener(evt, sync);
    });

    sync();
    })();
</script>
@endsection
