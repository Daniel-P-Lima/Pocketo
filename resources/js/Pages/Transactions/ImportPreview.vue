<script setup>
import { computed, reactive, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { longMonth } from '../../referenceMonth';

const props = defineProps({
    rows: Array,
    expenseCategories: Array,
    incomeCategories: Array,
    bank: Object,
    referenceMonth: String,
    errors: Object,
});

const items = reactive(props.rows.map(row => ({ ...row })));
const processing = ref(false);

const selected = computed(() => items.filter(item => item.selected));
const missingCategory = computed(() => selected.value.filter(item => !item.category_id));

const totalExpense = computed(() => sum(selected.value.filter(item => item.type === 'expense')));
const totalIncome = computed(() => sum(selected.value.filter(item => item.type === 'income')));

const firstError = computed(() => Object.values(props.errors ?? {})[0] ?? null);

function sum(list) {
    return list.reduce((total, item) => total + Number(item.amount), 0);
}

function categoriesFor(item) {
    return item.type === 'income' ? props.incomeCategories : props.expenseCategories;
}

function toggleType(item) {
    item.type = item.type === 'income' ? 'expense' : 'income';
    if (!categoriesFor(item).some(cat => cat.id === item.category_id)) {
        item.category_id = null;
    }
}

function toggleAll(value) {
    items.forEach(item => { item.selected = value; });
}

function formatBrl(amount) {
    return Number(amount).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function formatDate(date) {
    const [year, month, day] = date.split('-');
    return `${day}/${month}`;
}

function submit() {
    if (!selected.value.length || missingCategory.value.length) return;

    router.post('/transactions/import', {
        rows: selected.value.map(({ date, description, amount, type, category_id, bank_id, reference_month }) => ({
            date, description, amount, type, category_id, bank_id, reference_month,
        })),
    }, {
        onStart: () => { processing.value = true; },
        onFinish: () => { processing.value = false; },
    });
}
</script>

<template>
    <div class="px-4 py-4 space-y-4 pb-28">
        <div class="text-xs text-gray-500 space-y-1">
            <p v-if="referenceMonth">
                Fatura de <span class="font-semibold text-violet-600">{{ longMonth(referenceMonth) }}</span>:
                todas as compras contam neste mês.
            </p>
            <p v-if="bank" class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full" :style="{ backgroundColor: bank.color }"></span>
                Lançando no banco <span class="font-semibold text-gray-700">{{ bank.name }}</span>
            </p>
        </div>
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

        <div class="flex items-center justify-between text-xs text-gray-500">
            <span>{{ selected.length }} de {{ items.length }} selecionadas</span>
            <div class="flex gap-3">
                <button type="button" class="font-medium text-blue-600" @click="toggleAll(true)">Marcar todas</button>
                <button type="button" class="font-medium text-blue-600" @click="toggleAll(false)">Desmarcar</button>
            </div>
        </div>

        <p v-if="firstError" class="text-red-500 text-xs">{{ firstError }}</p>

        <div class="space-y-2">
            <div v-for="item in items" :key="item.id"
                 :class="['bg-white rounded-xl p-3 shadow-sm space-y-2 transition-opacity duration-200',
                          item.selected ? '' : 'opacity-50']">
                <div class="flex items-start gap-3">
                    <input type="checkbox" v-model="item.selected" class="mt-1 w-4 h-4 accent-violet-600">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ item.description }}</p>
                        <p class="text-xs text-gray-500">
                            {{ formatDate(item.date) }}
                            <span v-if="item.duplicate" class="ml-1 text-amber-600 font-medium">· já lançada</span>
                        </p>
                    </div>
                    <button type="button" @click="toggleType(item)"
                            :class="['text-sm font-bold whitespace-nowrap',
                                     item.type === 'income' ? 'text-emerald-600' : 'text-red-500']"
                            title="Alternar receita/despesa">
                        {{ item.type === 'income' ? '+' : '-' }} {{ formatBrl(item.amount) }}
                    </button>
                </div>

                <select v-if="item.selected" v-model="item.category_id"
                        :class="['w-full bg-white border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500',
                                 item.category_id ? 'border-gray-300' : 'border-red-400']">
                    <option :value="null" disabled>Selecione a categoria</option>
                    <option v-for="cat in categoriesFor(item)" :key="cat.id" :value="cat.id">
                        {{ cat.icon }} {{ cat.name }}
                    </option>
                </select>
            </div>
        </div>
    </div>

    <div class="fixed bottom-16 inset-x-0 px-4 py-3 bg-gray-50/95 backdrop-blur border-t border-gray-200 z-40">
        <p v-if="missingCategory.length" class="text-red-500 text-xs mb-2 text-center">
            {{ missingCategory.length }} transação(ões) sem categoria
        </p>
        <button type="button" @click="submit"
                :disabled="!selected.length || missingCategory.length > 0 || processing"
                class="w-full bg-blue-600 text-white font-semibold py-3.5 rounded-xl
                       transition-all duration-200
                       hover:bg-blue-700 hover:shadow-lg
                       active:scale-[0.98] active:bg-blue-800
                       disabled:opacity-50 disabled:hover:shadow-none disabled:active:scale-100">
            Lançar {{ selected.length }} transações
        </button>
    </div>
</template>
