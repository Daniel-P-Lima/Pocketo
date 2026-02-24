<script setup>
import { useForm, router } from '@inertiajs/vue3';
import {computed} from "vue";

const props = defineProps({
    stash: Object,
});

const form = useForm({
    name: props.stash.name,
    amount: props.stash.amount,
    goal_amount: props.stash.goal_amount,
    purpose: props.stash.purpose,
});

function submit() {
    form.put(`/stash/${props.stash.id}`);
}

function destroy() {
    if (confirm('Tem certeza que deseja excluir esta caixinha?')) {
        router.delete(`/stash/${props.stash.id}`);
    }
}

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

const displayGoalAmount = computed({
    get() {
        if (!form.goal_amount && form.goal_amount !== 0) return '';
        const num = parseFloat(form.goal_amount);
        if (isNaN(num)) return '';
        return num.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
    },
    set(val) {
        const digits = val.replace(/\D/g, '');
        const num = parseFloat(digits) || 0;
        form.goal_amount = parseFloat((num / 100).toFixed(2));
    },
});

</script>

<template>
    <div class="px-4 py-4 space-y-4">
        <form @submit.prevent="submit" class="space-y-4">

            <div class="space-y-1">
                <label for="name" class="text-sm font-medium text-gray-900">Nome</label>
                <input id="name" v-model="form.name" type="text" maxlength="60" required
                       class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900
                              placeholder:text-gray-400 shadow-sm transition-all duration-200
                              focus:outline-none focus:ring-2 focus:ring-[#B4A5D9]"
                       placeholder="Ex.: Reserva de emergência">
                <p v-if="form.errors.name" class="text-sm text-red-600">{{ form.errors.name }}</p>
            </div>

            <div class="space-y-1">
                <label for="amount" class="text-sm font-medium text-gray-900">Valor guardado</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">R$</span>
                    <input id="amount" type="text" inputmode="numeric"
                           :value="displayAmount"
                           @input="displayAmount = $event.target.value"
                           required
                           class="w-full rounded-xl border border-gray-200 bg-white pl-10 pr-4 py-3 text-sm text-gray-900
                                  placeholder:text-gray-400 shadow-sm transition-all duration-200
                                  focus:outline-none focus:ring-2 focus:ring-[#B4A5D9]"
                           placeholder="0,00">
                </div>
                <p v-if="form.errors.amount" class="text-sm text-red-600">{{ form.errors.amount }}</p>
            </div>

            <div class="space-y-1">
                <label for="goal_amount" class="text-sm font-medium text-gray-900">Objetivo</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">R$</span>
                    <input id="goal_amount" type="text" inputmode="numeric"
                           :value="displayGoalAmount"
                           @input="displayGoalAmount = $event.target.value"
                           required
                           class="w-full rounded-xl border border-gray-200 bg-white pl-10 pr-4 py-3 text-sm text-gray-900
                                  placeholder:text-gray-400 shadow-sm transition-all duration-200
                                  focus:outline-none focus:ring-2 focus:ring-[#B4A5D9]"
                           placeholder="0,00">
                </div>
                <p v-if="form.errors.goal_amount" class="text-sm text-red-600">{{ form.errors.goal_amount }}</p>
            </div>

            <div class="space-y-1">
                <label for="purpose" class="text-sm font-medium text-gray-900">Observações</label>
                <textarea id="purpose" v-model="form.purpose" rows="3" maxlength="255" required
                          class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900
                                 placeholder:text-gray-400 shadow-sm transition-all duration-200 resize-none
                                 focus:outline-none focus:ring-2 focus:ring-[#B4A5D9]"
                          placeholder="Ex.: Quando houver algum problema"></textarea>
                <p v-if="form.errors.purpose" class="text-sm text-red-600">{{ form.errors.purpose }}</p>
            </div>

            <div class="space-y-2 pt-2">
                <button type="submit" :disabled="form.processing"
                        class="w-full rounded-xl bg-blue-600 text-white px-4 py-3.5 text-sm font-semibold shadow-sm
                               transition-all duration-200 hover:bg-blue-700 active:scale-[0.98]
                               disabled:opacity-50">
                    {{ form.processing ? 'Salvando...' : 'Atualizar' }}
                </button>

                <button type="button" @click="destroy"
                        class="w-full text-red-600 font-medium py-3 rounded-xl border border-red-200
                               transition-all duration-200 hover:bg-red-50 active:scale-[0.98]">
                    Excluir caixinha
                </button>
            </div>
        </form>
    </div>
</template>
