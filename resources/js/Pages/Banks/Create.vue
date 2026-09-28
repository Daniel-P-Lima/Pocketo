<script setup>
import { useForm } from '@inertiajs/vue3';
import BankForm from '../../Components/BankForm.vue';

const form = useForm({
    name: '',
    color: '#820AD1',
});

function submit() {
    form.post('/banks');
}
</script>

<template>
    <form @submit.prevent="submit" class="px-4 py-4 space-y-5">
        <BankForm :form="form" />

        <button type="submit" :disabled="form.processing"
                class="w-full bg-blue-600 text-white font-semibold py-3.5 rounded-xl
                       transition-all duration-200
                       hover:bg-blue-700 hover:shadow-lg
                       active:scale-[0.98] active:bg-blue-800
                       disabled:opacity-50 disabled:hover:shadow-none disabled:active:scale-100">
            {{ form.processing ? 'Salvando...' : 'Salvar' }}
        </button>
    </form>
</template>
