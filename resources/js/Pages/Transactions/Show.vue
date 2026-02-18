<script setup>
import { Link, router } from '@inertiajs/vue3';

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

function formatDate(dateStr) {
    return new Date(dateStr).toLocaleDateString('pt-BR');
}

function destroy() {
    if (confirm('Tem certeza que deseja excluir esta transacao?')) {
        router.delete(`/transactions/${props.transaction.id}`);
    }
}
</script>

<template>
    <div class="px-4 py-4 space-y-4">
        <div class="bg-white rounded-2xl shadow-sm p-5 space-y-4">
            <div class="text-center">
                <p :class="['text-3xl font-bold', transaction.type === 'income' ? 'text-emerald-600' : 'text-red-500']">
                    {{ transaction.type === 'income' ? '+' : '-' }} {{ formatBrl(transaction.amount) }}
                </p>
                <span :class="['inline-block mt-2 px-3 py-1 rounded-full text-xs font-medium',
                               transaction.type === 'income' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700']">
                    {{ transaction.type === 'income' ? 'Receita' : 'Despesa' }}
                </span>
            </div>

            <hr class="border-gray-100">

            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Descricao</span>
                    <span class="text-sm font-medium text-gray-900">{{ transaction.description }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Categoria</span>
                    <span class="text-sm font-medium text-gray-900">
                        {{ transaction.category.icon }} {{ transaction.category.name }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Data</span>
                    <span class="text-sm font-medium text-gray-900">{{ formatDate(transaction.date) }}</span>
                </div>
                <div v-if="transaction.notes">
                    <span class="text-sm text-gray-500">Observacoes</span>
                    <p class="text-sm text-gray-700 mt-1">{{ transaction.notes }}</p>
                </div>
            </div>
        </div>

        <div class="space-y-2">
            <Link :href="`/transactions/${transaction.id}/edit`"
                  class="block w-full text-center bg-blue-600 text-white font-semibold py-3.5 rounded-xl
                         transition-all duration-200 hover:bg-blue-700 active:scale-[0.98]">
                Editar
            </Link>

            <button @click="destroy"
                    class="w-full text-red-600 font-medium py-3 rounded-xl border border-red-200
                           transition-all duration-200 hover:bg-red-50 active:scale-[0.98]">
                Excluir
            </button>
        </div>
    </div>
</template>
