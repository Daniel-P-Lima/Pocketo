<script setup>
import { useForm, router } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    budget: Object,
});

const form = useForm({
    amount: props.budget.amount,
});

const displayAmount = computed({
    get() {
        if (!form.amount && form.amount !== 0) return '';
        const num = parseFloat(form.amount);
        if (isNaN(num)) return '';
        return num.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
    },
    set(val) {
        const digits = val.replace(/\D/g, '');         
        const num = parseFloat(digits) || 0;           
        form.amount = parseFloat((num / 100).toFixed(2));
    },
});

function barColor(pct) {
    return pct >= 80 ? 'bg-red-500' : pct >= 60 ? 'bg-amber-500' : 'bg-emerald-500';
}

function textColor(pct) {
    return pct >= 80 ? 'text-red-500' : 'text-gray-900';
}

function formatBrl(amount) {
    if (!amount && amount !== 0) return '';
    return (amount).toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

function submit() {
    form.put(`/budgets/${props.budget.id}`);
}

function destroy() {
    if (confirm('Tem certeza que deseja excluir este orçamento?')) {
        router.delete(`/budgets/${props.budget.id}`);
    }
}
</script>

<template>
    <div class="px-4 py-4 space-y-5">

        <!-- Category (read-only) -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
            <div class="flex items-center gap-3 bg-gray-100 rounded-xl px-4 py-3">
                <span class="text-2xl">{{ budget.category.icon }}</span>
                <span class="text-sm font-medium text-gray-800">{{ budget.category.name }}</span>
            </div>
        </div>

        <!-- Current status -->
        <div class="bg-white rounded-xl p-4 shadow-sm space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Gasto atual</span>
                <span class="font-semibold" :class="textColor(budget.spent_percentage)">
                    {{ formatBrl(budget.spent) }}
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="h-2 rounded-full transition-all duration-300"
                     :class="barColor(budget.spent_percentage)"
                     :style="{ width: Math.min(budget.spent_percentage, 100) + '%' }">
                </div>
            </div>
        </div>

        <!-- Amount -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Limite mensal</label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">R$</span>
                <input type="text" inputmode="numeric"
                       :value="displayAmount"
                       @input="displayAmount = $event.target.value"
                       placeholder="0,00"
                       class="w-full bg-white border border-gray-300 rounded-xl pl-10 pr-4 py-3 text-lg font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
            </div>
            <p v-if="form.errors.amount" class="text-red-500 text-xs mt-1">{{ form.errors.amount }}</p>
        </div>

        <button type="button" @click="submit" :disabled="form.processing"
                class="w-full bg-blue-600 text-white font-semibold py-3.5 rounded-xl active:bg-blue-700 disabled:opacity-50">
            Atualizar
        </button>

        <button type="button" @click="destroy"
                class="w-full text-red-600 font-medium py-3 rounded-xl border border-red-200 active:bg-red-50">
            Excluir Orçamento
        </button>
    </div>
</template>