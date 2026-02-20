<script setup>
import { Link } from '@inertiajs/vue3';
import CreateButton from '../../Components/CreateButton.vue';

defineProps({
    expenseCategories: {
        type: Array,
        default: () => [],
    },
    incomeCategories: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <div class="px-4 py-4 space-y-6">

        <!-- Expense categories -->
        <div>
            <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2 px-1">Despesas</h2>
            <div class="space-y-2">
                <Link v-for="cat in expenseCategories" :key="cat.id"
                      :href="`/categories/${cat.id}/edit`"
                      class="flex items-center gap-3 bg-white rounded-xl px-4 py-3 shadow-sm active:bg-gray-50 transition-colors duration-150">
                    <span class="text-2xl">{{ cat.icon }}</span>
                    <span class="flex-1 text-sm font-medium text-gray-800">{{ cat.name }}</span>
                    <span class="w-3 h-3 rounded-full flex-shrink-0" :style="{ backgroundColor: cat.color }"></span>
                    <svg v-if="cat.is_default" class="w-4 h-4 text-gray-300 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                    </svg>
                </Link>
            </div>
        </div>

        <!-- Income categories -->
        <div>
            <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2 px-1">Receitas</h2>
            <div class="space-y-2">
                <Link v-for="cat in incomeCategories" :key="cat.id"
                      :href="`/categories/${cat.id}/edit`"
                      class="flex items-center gap-3 bg-white rounded-xl px-4 py-3 shadow-sm active:bg-gray-50 transition-colors duration-150">
                    <span class="text-2xl">{{ cat.icon }}</span>
                    <span class="flex-1 text-sm font-medium text-gray-800">{{ cat.name }}</span>
                    <span class="w-3 h-3 rounded-full flex-shrink-0" :style="{ backgroundColor: cat.color }"></span>
                    <svg v-if="cat.is_default" class="w-4 h-4 text-gray-300 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                    </svg>
                </Link>
            </div>
        </div>
    </div>

    <!-- FAB -->
    <CreateButton link="/categories/create"></CreateButton>

</template>

<style scoped>
.addButton:hover svg {
    transform: rotate(90deg);
}
</style>
