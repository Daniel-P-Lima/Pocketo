<script setup>
defineProps({
    banks: { type: Array, default: () => [] },
    error: { type: String, default: null },
});

const model = defineModel();
</script>

<template>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Banco <span class="font-normal text-gray-400">(opcional)</span>
        </label>
        <div class="flex gap-2 flex-wrap">
            <button type="button" @click="model = null"
                    :class="['px-3 py-2 rounded-xl border text-xs font-medium transition-all duration-200 active:scale-[0.95]',
                             model == null ? 'ring-2 ring-blue-500 border-blue-500 shadow-md text-gray-900' : 'border-gray-200 text-gray-500']">
                Nenhum
            </button>
            <button v-for="bank in banks" :key="bank.id" type="button" @click="model = bank.id"
                    :class="['flex items-center gap-1.5 px-3 py-2 rounded-xl border text-xs font-medium transition-all duration-200 active:scale-[0.95]',
                             model === bank.id ? 'ring-2 ring-blue-500 border-blue-500 shadow-md text-gray-900' : 'border-gray-200 text-gray-600']">
                <span class="w-2.5 h-2.5 rounded-full" :style="{ backgroundColor: bank.color }"></span>
                {{ bank.name }}
            </button>
        </div>
        <p v-if="error" class="text-red-500 text-xs mt-1">{{ error }}</p>
    </div>
</template>
