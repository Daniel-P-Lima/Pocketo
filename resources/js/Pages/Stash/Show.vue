<script setup>
import { computed } from 'vue';

const props = defineProps({
    stash: Array,
});

const stashAmount = computed(() => props.stash.amount);
const stashGoalAmount = computed(() => props.stash.goal_amount);
const stashPurpose = computed(() => props.stash.purpose);
const stashDate = computed(() => new Date(props.stash.created_at).toString());

function formatDate(dateStr) {
    return new Date(dateStr).toLocaleDateString('pt-BR');
}

</script>
<template>
    <div class="px-4 py-4 space-y-4">
        <div class="bg-white rounded-2xl shadow-sm p-5 space-y-4">
            <div class="text-center">
                <p class="text-3xl font-bold">
                    {{ stashAmount }}
                </p>
                <p class="text-3xl font-bold">
                    {{ stashGoalAmount }}
                </p>
            </div>
            <div v-if="stashPurpose" class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Observações</span>
                        <span class="text-sm font-medium text-gray-900">
                            {{ stashPurpose}}
                        </span>
                    </div>

                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Data</span>
                    <span class="text-sm font-medium text-gray-900">{{ formatDate(stashDate) }}</span>
                </div>
            </div>
        </div>
    </div>
</template>