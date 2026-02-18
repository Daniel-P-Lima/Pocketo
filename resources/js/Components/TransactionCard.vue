<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    transaction: Object,
});

function formatBrl(amount) {
    if (!amount && amount !== 0) return '';
    const num = parseFloat(amount);
    if (isNaN(num)) return '';
    return num.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}
</script>

<template>
    <Link :href="`/transactions/${transaction.id}`"
          class="flex items-center gap-3 bg-white rounded-xl px-4 py-3 shadow-sm active:bg-gray-50">
        <div class="flex items-center justify-center w-10 h-10 rounded-full text-xl"
             :style="{ backgroundColor: transaction.category.color + '20' }">
            {{ transaction.category.icon }}
        </div>

        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900 truncate">{{ transaction.description }}</p>
            <p class="text-xs text-gray-500">
                {{ transaction.category.name }} &middot; {{ new Date(transaction.date).toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit' }) }}
            </p>
        </div>

        <span :class="['text-sm font-semibold whitespace-nowrap', transaction.type === 'income' ? 'text-emerald-600' : 'text-red-500']">
            {{ transaction.type === 'income' ? '+' : '-' }} {{ formatBrl(transaction.amount) }}
        </span>
    </Link>
</template>
