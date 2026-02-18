<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    stashes: Array,
});

const totalStashes = computed(() => props.stashes.length);
const totalAmount = computed(() => props.stashes.reduce((sum, s) => sum + Number(s.amount), 0));

function formatBrl(value) {
    return 'R$ ' + Number(value).toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

function progress(stash) {
    if (!stash.goal_amount) return 0;
    return (stash.amount / stash.goal_amount) * 100;
}

function badge(stash) {
    const p = progress(stash);
    if (p >= 70) return { bg: 'bg-emerald-50', text: 'text-emerald-700', label: 'Forte' };
    if (p >= 30) return { bg: 'bg-amber-50', text: 'text-amber-700', label: 'Crescendo' };
    return { bg: 'bg-gray-100', text: 'text-gray-700', label: 'Começando' };
}

function formatDate(dateStr) {
    return new Date(dateStr).toLocaleDateString('pt-BR');
}
</script>

<template>
    <div class="px-4 py-4 space-y-4">
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                <p class="text-xs text-gray-500">Caixinhas</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ totalStashes }}</p>
            </div>

            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                <p class="text-xs text-gray-500">Total guardado</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900">
                    {{ formatBrl(totalAmount) }}
                </p>
            </div>
        </div>

        <div class="flex items-center justify-between mb-10">
            <h2 class="text-base font-semibold text-gray-900">Minhas caixinhas</h2>
        </div>

        <div class="space-y-3">
            <template v-if="stashes.length">
                <Link v-for="stash in stashes" :key="stash.id"
                      :href="`/stash/${stash.id}`"
                      class="block bg-white rounded-2xl p-4 shadow-sm border border-gray-100 hover:border-gray-200 active:scale-[0.99] transition">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 rounded-2xl bg-violet-50 border border-violet-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-violet-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 10v2m9-6a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ stash.name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ stash.purpose }}</p>
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-violet-600 rounded-full"
                                         :style="{ width: progress(stash) + '%' }"></div>
                                </div>
                                <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                                    <span>Criada: {{ formatDate(stash.created_at) }}</span>
                                    <span class="px-2 py-1 rounded-full"
                                          :class="[badge(stash).bg, badge(stash).text]">
                                        {{ badge(stash).label }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="text-right shrink-0">
                            <p class="text-xs text-gray-500">Guardado</p>
                            <p class="text-lg font-semibold text-gray-900">{{ formatBrl(stash.amount) }}</p>
                            <p class="text-xs text-gray-500">Objetivo</p>
                            <p class="text-lg font-semibold text-gray-900">{{ formatBrl(stash.goal_amount) }}</p>
                        </div>
                    </div>
                </Link>
            </template>

            <div v-else class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center">
                <p class="text-sm font-semibold text-gray-900">Nenhuma caixinha ainda</p>
                <p class="mt-1 text-sm text-gray-500">Crie uma caixinha para separar seu dinheiro por objetivo.</p>
            </div>
        </div>
    </div>

    <Link href="/stash/create"
          class="fixed bottom-20 right-4 w-14 h-14 bg-violet-600 text-white rounded-full shadow-lg flex items-center justify-center active:bg-violet-700 z-40 addButton">
        <svg class="w-7 h-7 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
    </Link>
</template>

<style scoped>

.addButton:hover svg {
    transform: rotate(90deg);
}
</style>
