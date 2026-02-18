<script setup>
import { computed, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    expenseCategories: Array,
    incomeCategories: Array,
});

const form = useForm({
    type: 'expense',
    amount: '',
    description: '',
    category_id: '',
    date: new Date().toISOString().slice(0, 10),
    notes: '',
});

const visibleCategories = computed(() =>
    form.type === 'income' ? props.incomeCategories : props.expenseCategories
);

// Reset category when switching type
watch(() => form.type, () => {
    form.category_id = null;
});

// Currency input formatting
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
    form.post('/transactions');
}
</script>

<template>
    <form @submit.prevent="submit" class="px-4 py-4 space-y-5">

        <!-- Type toggle -->
        <div>
            <div class="flex gap-2">
                <label class="flex-1 cursor-pointer">
                    <input type="radio" v-model="form.type" value="expense" class="peer hidden">
                    <div class="text-center py-3 rounded-xl border border-gray-300 text-sm font-semibold cursor-pointer
                                transition-all duration-200
                                peer-checked:bg-red-500 peer-checked:text-white peer-checked:border-red-500 peer-checked:shadow-md
                                active:scale-[0.97]">
                        Despesa
                    </div>
                </label>
                <label class="flex-1 cursor-pointer">
                    <input type="radio" v-model="form.type" value="income" class="peer hidden">
                    <div class="text-center py-3 rounded-xl border border-gray-300 text-sm font-semibold cursor-pointer
                                transition-all duration-200
                                peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-500 peer-checked:shadow-md
                                active:scale-[0.97]">
                        Receita
                    </div>
                </label>
            </div>
        </div>

        <!-- Amount -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Valor</label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">R$</span>
                <input type="text" inputmode="numeric"
                       v-model="displayAmount"
                       class="w-full bg-white border border-gray-300 rounded-xl pl-10 pr-4 py-3 text-lg font-semibold
                              transition-all duration-200
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:shadow-md"
                       placeholder="0,00">
            </div>
            <p v-if="form.errors.amount" class="text-red-500 text-xs mt-1">{{ form.errors.amount }}</p>
        </div>

        <!-- Description -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Descricao</label>
            <input type="text" v-model="form.description" required maxlength="255"
                   class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm
                          transition-all duration-200
                          focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:shadow-md"
                   placeholder="Ex: Supermercado">
            <p v-if="form.errors.description" class="text-red-500 text-xs mt-1">{{ form.errors.description }}</p>
        </div>

        <!-- Category -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
            <Transition name="fade" mode="out-in">
                <div :key="form.type" class="grid grid-cols-4 gap-2">
                    <label v-for="cat in visibleCategories" :key="cat.id" class="cursor-pointer">
                        <input type="radio" v-model="form.category_id" :value="cat.id" class="peer hidden">
                        <div class="flex flex-col items-center gap-1 py-2.5 rounded-xl border border-gray-200 text-xs
                                    transition-all duration-200
                                    peer-checked:ring-2 peer-checked:ring-blue-500 peer-checked:border-blue-500 peer-checked:shadow-md
                                    hover:border-gray-300 hover:shadow-sm
                                    active:scale-[0.95]">
                            <span class="text-xl">{{ cat.icon }}</span>
                            <span class="text-gray-600 truncate w-full text-center px-1">{{ cat.name }}</span>
                        </div>
                    </label>
                </div>
            </Transition>
            <p v-if="form.errors.category_id" class="text-red-500 text-xs mt-1">{{ form.errors.category_id }}</p>
        </div>

        <!-- Date -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Data</label>
            <input type="date" v-model="form.date" required
                   class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm
                          transition-all duration-200
                          focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:shadow-md">
            <p v-if="form.errors.date" class="text-red-500 text-xs mt-1">{{ form.errors.date }}</p>
        </div>

        <!-- Notes -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Observacoes (opcional)</label>
            <textarea v-model="form.notes" rows="2" maxlength="1000"
                      class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm resize-none
                             transition-all duration-200
                             focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:shadow-md"
                      placeholder="Detalhes adicionais..."></textarea>
        </div>

        <button type="submit" :disabled="form.processing"
                class="w-full bg-blue-600 text-white font-semibold py-3.5 rounded-xl
                       transition-all duration-200
                       hover:bg-blue-700 hover:shadow-lg
                       active:scale-[0.98] active:bg-blue-800
                       disabled:opacity-50 disabled:hover:shadow-none disabled:active:scale-100">
            Salvar
        </button>
    </form>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease, transform 0.2s ease;
}
.fade-enter-from {
    opacity: 0;
    transform: translateY(4px);
}
.fade-leave-to {
    opacity: 0;
    transform: translateY(-4px);
}
</style>