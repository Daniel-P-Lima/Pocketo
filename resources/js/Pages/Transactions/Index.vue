<script setup>
import { computed } from 'vue';
import CreateButton from "../../Components/CreateButton.vue";
import { Link } from '@inertiajs/vue3';

import MonthPicker from '../../Components/MonthPicker.vue';
import TransactionCard from '../../Components/TransactionCard.vue';
import EmptyState from '../../Components/EmptyState.vue';

const props = defineProps({
    transactions: Object,
    month: Number,
    year: Number,
    type: String,
    bank: Number,
    banks: { type: Array, default: () => [] },
    totalIncome: Number,
    totalExpense: Number,
})

function filterUrl({ type = props.type ?? '', bank = props.bank ?? '' } = {}) {
    return `/transactions?month=${props.month}&year=${props.year}&type=${type}&bank=${bank}`;
}

const typeFilters = [
    { value: '', label: 'Todos' },
    { value: 'income', label: 'Receitas' },
    { value: 'expense', label: 'Despesas' },
]

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
    <div class="px-4 py-4 space-y-4">
        <MonthPicker :transaction-month="month" :transaction-year="year" route="/transactions" />

        <div class="flex gap-3">
            <div class="flex-1 bg-white rounded-xl p-3 shadow-sm text-center">
                <p class="text-xs text-gray-500">Receitas</p>
                <p class="text-sm font-bold text-emerald-600">{{ formatBrl(totalIncome) }}</p>
            </div>
            <div class="flex-1 bg-white rounded-xl p-3 shadow-sm text-center">
                <p class="text-xs text-gray-500">Despesas</p>
                <p class="text-sm font-bold text-red-500">{{ formatBrl(totalExpense) }}</p>
            </div>
        </div>

        <Link href="/transactions/import"
              class="flex items-center justify-center gap-2 bg-white rounded-xl py-2.5 shadow-sm text-sm font-medium text-violet-600
                     transition-all duration-200 hover:shadow-md active:scale-[0.98]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M16 8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Importar fatura (CSV)
        </Link>

        <!-- Type filter tabs -->
        <div class="flex gap-1 bg-gray-200 rounded-xl p-1">
            <Link v-for="filter in typeFilters" :key="filter.value"
                :href="filterUrl({ type: filter.value })" preserve-scroll :class="['flex-1 text-center py-2 rounded-lg text-sm font-medium',
                    'transition-all duration-200',
                    (type ?? '') === filter.value
                        ? 'bg-white text-gray-900 shadow-sm'
                        : 'text-gray-600 hover:text-gray-800 active:bg-white/50']">
                {{ filter.label }}
            </Link>
        </div>

        <!-- Bank filter -->
        <div v-if="banks.length" class="flex gap-2 overflow-x-auto -mx-4 px-4 pb-1">
            <Link :href="filterUrl({ bank: '' })" preserve-scroll
                  :class="['px-3 py-1.5 rounded-full text-xs font-medium whitespace-nowrap border transition-all duration-200',
                           !bank ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-600 border-gray-200']">
                Todos os bancos
            </Link>
            <Link v-for="b in banks" :key="b.id" :href="filterUrl({ bank: b.id })" preserve-scroll
                  :class="['flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium whitespace-nowrap border transition-all duration-200',
                           bank === b.id ? 'text-white border-transparent' : 'bg-white text-gray-600 border-gray-200']"
                  :style="bank === b.id ? { backgroundColor: b.color } : {}">
                <span v-if="bank !== b.id" class="w-2 h-2 rounded-full" :style="{ backgroundColor: b.color }"></span>
                {{ b.name }}
            </Link>
        </div>

        <!-- Transaction list -->
        <Transition name="fade" mode="out-in">
            <div :key="(type ?? '') + (bank ?? '') + month + year">
                <EmptyState v-if="!transactions.length" message="Nenhuma transacao neste mes."
                    action-url="/transactions/create" action-label="Adicionar Transacao" />

                <TransitionGroup v-else name="list" tag="div" class="space-y-2">
                    <TransactionCard v-for="(transaction, i) in transactions" :key="transaction.id"
                        :transaction="transaction" :style="{ transitionDelay: `${i * 30}ms` }" />
                </TransitionGroup>
            </div>
        </Transition>
    </div>

    <CreateButton link="/transactions/create"></CreateButton>
    
</template>

<style scoped>
/* Tab content swap */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease, transform 0.2s ease;
}

.fade-enter-from {
    opacity: 0;
    transform: translateY(6px);
}

.fade-leave-to {
    opacity: 0;
    transform: translateY(-6px);
}

/* Staggered list items */
.list-enter-active {
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.list-enter-from {
    opacity: 0;
    transform: translateY(12px);
}

/* FAB icon rotate on hover */
.addButton:hover svg {
    transform: rotate(90deg);
}
</style>