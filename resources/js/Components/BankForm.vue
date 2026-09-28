<script setup>
const props = defineProps({
    form: { type: Object, required: true },
});

const palette = ['#820AD1', '#EC7000', '#FF7A00', '#CC092F', '#EC0000', '#F8D117', '#005CA9', '#242424', '#10B981', '#64748B'];
</script>

<template>
    <!-- Name -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
        <input type="text" v-model="form.name" required maxlength="100"
               class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm
                      transition-all duration-200
                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:shadow-md"
               placeholder="Ex: Nubank">
        <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
    </div>

    <!-- Color -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Cor</label>
        <div class="flex gap-2 flex-wrap items-center">
            <button v-for="color in palette" :key="color" type="button"
                    @click="form.color = color"
                    :class="['w-10 h-10 rounded-full border-2 transition-all duration-200',
                             form.color.toLowerCase() === color.toLowerCase()
                                ? 'border-gray-800 ring-2 ring-offset-2 ring-gray-400'
                                : 'border-transparent']"
                    :style="{ backgroundColor: color }">
            </button>
            <input type="color" v-model="form.color" title="Outra cor"
                   class="w-10 h-10 rounded-full cursor-pointer bg-transparent border border-gray-300">
        </div>
        <p v-if="form.errors.color" class="text-red-500 text-xs mt-1">{{ form.errors.color }}</p>
    </div>
</template>
