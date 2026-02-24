<script setup>
import { computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    stash: Object,
});

const progress = computed(() => {
    if (!props.stash.goal_amount) return 0;
    return Math.min((props.stash.amount / props.stash.goal_amount) * 100, 100);
});

function formatBrl(value) {
    return 'R$ ' + Number(value).toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

function formatDate(dateStr) {
    return new Date(dateStr).toLocaleDateString('pt-BR');
}

function destroy() {
    if (confirm('Tem certeza que deseja excluir esta caixinha?')) {
        router.delete(`/stash/${props.stash.id}`);
    }
}
</script>

<template>
    <div class="px-4 py-4 space-y-4">

        <!-- Hero card -->
        <div class="bg-white rounded-2xl shadow-sm p-5 space-y-4">
            <div class="text-center">
                <p class="text-3xl font-bold text-violet-700">{{ formatBrl(stash.amount) }}</p>
                <p class="text-sm text-gray-500 mt-1">guardado de {{ formatBrl(stash.goal_amount) }}</p>
            </div>

            <div class="space-y-1">
                <div class="h-2.5 w-full bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-violet-600 rounded-full transition-all duration-500"
                         :style="{ width: progress + '%' }"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-400">
                    <span>{{ progress.toFixed(0) }}% do objetivo</span>
                    <span>{{ formatBrl(stash.goal_amount) }}</span>
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="bg-white rounded-2xl shadow-sm p-5 space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500">Nome</span>
                <span class="text-sm font-medium text-gray-900">{{ stash.name }}</span>
            </div>

            <hr class="border-gray-100">

            <div class="flex justify-between items-start gap-4">
                <span class="text-sm text-gray-500 shrink-0">Observações</span>
                <span class="text-sm font-medium text-gray-900 text-right">{{ stash.purpose }}</span>
            </div>

            <hr class="border-gray-100">

            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500">Criada em</span>
                <span class="text-sm font-medium text-gray-900">{{ formatDate(stash.created_at) }}</span>
            </div>
        </div>

        <!-- Actions -->
        <div class="space-y-2">
            <Link :href="`/stash/${stash.id}/edit`"
                  class="block w-full text-center bg-blue-600 text-white font-semibold py-3.5 rounded-xl
                         transition-all duration-200 hover:bg-blue-700 active:scale-[0.98]">
                Editar
            </Link>

            <button @click="destroy"
                    class="w-full text-red-600 font-medium py-3 rounded-xl border border-red-200
                           transition-all duration-200 hover:bg-red-50 active:scale-[0.98]">
                Excluir caixinha
            </button>
        </div>
    </div>
</template>
