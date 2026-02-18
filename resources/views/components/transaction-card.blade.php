@props(['transaction'])

<a href="{{ route('transactions.show', $transaction) }}" class="flex items-center gap-3 bg-white rounded-xl px-4 py-3 shadow-sm active:bg-gray-50">
    <div class="flex items-center justify-center w-10 h-10 rounded-full text-xl"
         style="background-color: {{ $transaction->category->color }}20;">
        {{ $transaction->category->icon }}
    </div>

    <div class="flex-1 min-w-0">
        <p class="text-sm font-medium text-gray-900 truncate">{{ $transaction->description }}</p>
        <p class="text-xs text-gray-500">{{ $transaction->category->name }} &middot; {{ $transaction->date->format('d/m') }}</p>
    </div>

    <span class="text-sm font-semibold whitespace-nowrap {{ $transaction->type === 'income' ? 'text-emerald-600' : 'text-red-500' }}">
        {{ $transaction->type === 'income' ? '+' : '-' }} {{ $transaction->formatted_amount }}
    </span>
</a>
