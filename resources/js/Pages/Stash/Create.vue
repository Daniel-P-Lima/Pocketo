<script setup>
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    amount: '',
    goal_amount: '',
    purpose: '',
});

function submit() {
    form.post('/stash');
}
</script>

<template>
    <div class="px-4 py-4 space-y-4">
        <form @submit.prevent="submit" class="space-y-4 max-w-md flex flex-col place-self-center">
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
                <label for="amount" class="text-sm font-medium text-gray-900">Valor inicial (R$)</label>
                <input id="amount" v-model="form.amount" type="number" min="0" step="0.01" required
                       class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900
                              placeholder:text-gray-400 shadow-sm transition-all duration-200
                              focus:outline-none focus:ring-2 focus:ring-[#B4A5D9]"
                       placeholder="0,00">
                <p v-if="form.errors.amount" class="text-sm text-red-600">{{ form.errors.amount }}</p>
            </div>

            <div class="space-y-1">
                <label for="goal_amount" class="text-sm font-medium text-gray-900">Objetivo (R$)</label>
                <input id="goal_amount" v-model="form.goal_amount" type="number" min="0" step="0.01" required
                       class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900
                              placeholder:text-gray-400 shadow-sm transition-all duration-200
                              focus:outline-none focus:ring-2 focus:ring-[#B4A5D9]"
                       placeholder="0,00">
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

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" :disabled="form.processing"
                        class="flex-1 rounded-xl bg-[#3F2D56] text-white px-4 py-3 text-sm font-semibold shadow-sm
                               transition-all duration-200
                               active:scale-[0.98] hover:bg-[#342447]
                               disabled:opacity-50">
                    Criar caixinha
                </button>

                <Link href="/stash"
                      class="flex-1 rounded-xl bg-white text-gray-900 px-4 py-3 text-sm font-semibold shadow-sm
                             border border-gray-200 text-center transition-all duration-200
                             active:scale-[0.98] hover:bg-gray-50">
                    Cancelar
                </Link>
            </div>
        </form>
    </div>
</template>
