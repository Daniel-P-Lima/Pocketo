@props(['message', 'actionUrl' => null, 'actionLabel' => null])

<div class="flex flex-col items-center justify-center py-12 px-6">
    <div class="text-6xl mb-4 opacity-30">
        {{ $icon ?? '📋' }}
    </div>
    <p class="text-gray-500 text-sm text-center mb-4">{{ $message }}</p>
    @if($actionUrl)
        <a href="{{ $actionUrl }}"
           class="inline-flex items-center gap-2 bg-blue-600 text-white text-sm font-medium px-5 py-2.5 rounded-xl active:bg-blue-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ $actionLabel }}
        </a>
    @endif
</div>
