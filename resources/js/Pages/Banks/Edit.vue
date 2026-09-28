<script setup>
import { useForm, router } from '@inertiajs/vue3';
import BankForm from '../../Components/BankForm.vue';

const props = defineProps({
    bank: { type: Object, required: true },
    canDelete: { type: Boolean, default: false },
});

const form = useForm({
    name: props.bank.name,
    color: props.bank.color,
});

function submit() {
    form.put(`/banks/${props.bank.id}`);
}

function destroy() {
    if (confirm('Tem certeza que deseja excluir este banco?')) {
        router.delete(`/banks/${props.bank.id}`);
    }
}
</script>

<template>
    <form @submit.prevent="submit" class="px-4 py-4 space-y-5">
        <BankForm :form="form" />

        <div class="space-y-2">
            <button type="submit" :disabled="form.processing"
                    class="w-full bg-blue-600 text-white font-semibold py-3.5 rounded-xl
                           transition-all duration-200
                           hover:bg-blue-700 hover:shadow-lg
                           active:scale-[0.98] active:bg-blue-800
                           disabled:opacity-50 disabled:hover:shadow-none disabled:active:scale-100">
                {{ form.processing ? 'Salvando...' : 'Atualizar' }}
            </button>

            <button v-if="canDelete" type="button" @click="destroy"
                    class="w-full bg-white text-red-500 font-semibold py-3.5 rounded-xl border border-red-200
                           transition-all duration-200
                           hover:bg-red-50 hover:border-red-300
                           active:scale-[0.98]">
                Excluir banco
            </button>
        </div>
    </form>
</template>
