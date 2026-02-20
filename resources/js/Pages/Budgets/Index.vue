<script setup>
import { Link } from '@inertiajs/vue3';
import CreateButton from "../../Components/CreateButton.vue";
import EmptyState from "../../Components/EmptyState.vue";

const props = defineProps({
    budgets: Array,
    month: String,
    year: String,
    totalBudgeted: Number,
    totalSpent: Number,
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

function pct(budget) {
    return budget.spent_percentage;
}

function barColor(budget) {
    const p = pct(budget);
    return p >= 80 ? 'bg-red-500' : (p >= 60 ? 'bg-amber-500' : 'bg-emerald-500');
}

function textColor(budget) {
    const p = pct(budget);
    return p >= 80 ? 'text-red-600' : (p >= 60 ? 'text-amber-600' : 'text-emerald-600');
}
</script>

<template>
    <div class="px-4 py-4 space-y-4">
        <div v-if="budgets.length > 0" class="bg-white rounded-xl p-4 shadow-sm">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm text-gray-500">Total Orçado</span>
                <span class="text-sm font-bold text-gray-900">{{ formatBrl(totalBudgeted) }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500">Total Gasto</span>
                <span class="text-sm font-bold" :class="totalSpent > totalBudgeted ? 'text-red-500' : 'text-emerald-600'">
                    {{ formatBrl(totalSpent) }}
                </span>
            </div>
        </div>

        <EmptyState v-if="budgets.length === 0" message="Nenhum orçamento definido para este mês." action-url="/budgets/create" action-label="Definir Orçamento" />

        <div v-else class="flex flex-col gap-3">
            <div v-for="budget in budgets" :key="budget.id" class="bg-white rounded-xl px-4 py-3 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">{{ budget.category.icon }}</span>
                        <span class="text-sm font-medium text-gray-800">{{ budget.category.name }}</span>
                    </div>
                    <Link :href="`/budgets/${budget.id}`" class="text-gray-400 p-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </Link>
                </div>

                <div class="w-full bg-gray-200 rounded-full h-2 mb-1.5">
                    <div class="h-2 rounded-full transition-all duration-300"
                        :class="barColor(budget)"
                        :style="{ width: Math.min(pct(budget), 100) + '%' }">
                    </div>
                </div>

                <div class="flex justify-between text-xs">
                    <span class="font-medium" :class="textColor(budget)">
                        {{ formatBrl(budget.spent) }} de {{ formatBrl(budget.amount) }}
                    </span>
                    <span class="text-gray-500">{{ pct(budget) }}%</span>
                </div>
            </div>
        </div>

        <CreateButton link="/budgets/create" />
    </div>
</template>
