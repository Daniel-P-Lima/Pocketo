<script setup>
import { Link } from '@inertiajs/vue3';
import CreateButton from '../../Components/CreateButton.vue';
import EmptyState from '../../Components/EmptyState.vue';

defineProps({
    banks: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <div class="px-4 py-4 space-y-2">
        <EmptyState v-if="!banks.length" message="Nenhum banco cadastrado."
                    action-url="/banks/create" action-label="Adicionar Banco" />

        <Link v-for="bank in banks" :key="bank.id"
              :href="`/banks/${bank.id}/edit`"
              class="flex items-center gap-3 bg-white rounded-xl px-4 py-3 shadow-sm active:bg-gray-50 transition-colors duration-150">
            <span class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0"
                  :style="{ backgroundColor: bank.color }">
                {{ bank.name.charAt(0) }}
            </span>
            <span class="flex-1 text-sm font-medium text-gray-800">{{ bank.name }}</span>
            <span class="text-xs text-gray-400">{{ bank.transactions_count }} transações</span>
        </Link>
    </div>

    <CreateButton link="/banks/create"></CreateButton>
</template>
