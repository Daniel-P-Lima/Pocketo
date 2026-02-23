<script setup>
import { useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    categories: Array,
    month: Number,
    year: Number,
});

const form = useForm({
    category_id: props.categories[0]?.id ?? null,
    amount: null,
    month: props.month,
    year: props.year,
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

function submit() {
    form.post('/budgets');
}
</script>

<template>
    <div class="px-4 py-4 space-y-5">

        <!-- Category grid -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>

            <p v-if="categories.length === 0"
               class="text-sm text-gray-500 bg-gray-100 rounded-xl p-4 text-center">
                Todas as categorias já possuem orçamento neste mês.
            </p>

            <div v-else class="grid grid-cols-3 gap-2">
                <button v-for="cat in categories" :key="cat.id" type="button"
                    @click="form.category_id = cat.id"
                    :class="['flex flex-col items-center gap-1 py-3 rounded-xl border text-xs transition-all',
                        form.category_id === cat.id
                            ? 'ring-2 ring-blue-500 border-blue-500 bg-blue-50'
                            : 'border-gray-200 active:bg-gray-50']">
                    <span class="text-xl">{{ cat.icon }}</span>
                    <span class="text-gray-600 font-medium">{{ cat.name }}</span>
                </button>
            </div>
            <p v-if="form.errors.category_id" class="text-red-500 text-xs mt-1">{{ form.errors.category_id }}</p>
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

        <button v-if="categories.length > 0"
                type="button" @click="submit" :disabled="form.processing"
                class="w-full bg-blue-600 text-white font-semibold py-3.5 rounded-xl active:bg-blue-700 disabled:opacity-50">
            Salvar
        </button>
    </div>
</template>