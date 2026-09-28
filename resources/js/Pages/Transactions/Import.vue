<script setup>
import { useForm } from '@inertiajs/vue3';
import BankPicker from '../../Components/BankPicker.vue';
import { monthFromFilename } from '../../referenceMonth';

const props = defineProps({
    banks: { type: Array, default: () => [] },
    defaultBankId: { type: Number, default: null },
});

const form = useForm({
    file: null,
    bank_id: props.defaultBankId,
    reference_month: new Date().toISOString().slice(0, 7),
});

function onFileChange(event) {
    form.file = event.target.files[0] ?? null;
    form.clearErrors('file');

    // Nubank names the file after the bill due date
    const month = monthFromFilename(form.file?.name);
    if (month) form.reference_month = month;
}

function submit() {
    form.post('/transactions/import/upload');
}
</script>

<template>
    <form @submit.prevent="submit" class="px-4 py-4 space-y-5">
        <div class="bg-white rounded-xl p-4 shadow-sm space-y-2">
            <p class="text-sm font-medium text-gray-900">Fatura do Nubank</p>
            <p class="text-xs text-gray-500">
                No app do Nubank, abra a fatura e exporte em CSV. O arquivo deve ter as colunas
                <span class="font-mono">date</span>, <span class="font-mono">title</span> e
                <span class="font-mono">amount</span>. Você poderá revisar as transações antes de lançar.
            </p>
        </div>

        <div>
            <label class="flex flex-col items-center justify-center gap-2 w-full py-8 bg-white border-2 border-dashed border-gray-300 rounded-xl cursor-pointer
                          transition-all duration-200 hover:border-violet-400 hover:bg-violet-50/40">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M16 8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <span class="text-sm text-gray-600">{{ form.file ? form.file.name : 'Selecionar arquivo .csv' }}</span>
                <input type="file" accept=".csv,text/csv" class="hidden" @change="onFileChange">
            </label>
            <p v-if="form.errors.file" class="text-red-500 text-xs mt-1">{{ form.errors.file }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Mês de vencimento da fatura</label>
            <input type="month" v-model="form.reference_month" required
                   class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm
                          transition-all duration-200
                          focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:shadow-md">
            <p class="text-xs text-gray-400 mt-1">Todas as compras da fatura contam neste mês. Preenchido pelo nome do arquivo.</p>
            <p v-if="form.errors.reference_month" class="text-red-500 text-xs mt-1">{{ form.errors.reference_month }}</p>
        </div>

        <BankPicker v-model="form.bank_id" :banks="banks" :error="form.errors.bank_id" />

        <button type="submit" :disabled="!form.file || form.processing"
                class="w-full bg-blue-600 text-white font-semibold py-3.5 rounded-xl
                       transition-all duration-200
                       hover:bg-blue-700 hover:shadow-lg
                       active:scale-[0.98] active:bg-blue-800
                       disabled:opacity-50 disabled:hover:shadow-none disabled:active:scale-100">
            Continuar
        </button>
    </form>
</template>
