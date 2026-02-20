<script setup>
import { useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    category: { type: Object, required: true },
    icons: { type: Array, required: true },
    colors: { type: Array, required: true },
    types: { type: Array, required: true },
    canDelete: { type: Boolean, default: false },
});

const typeLabels = { expense: 'Despesa', income: 'Receita', both: 'Ambos' };

const form = useForm({
    name: props.category.name,
    type: props.category.type,
    icon_id: props.category.icon_id,
    color: props.category.color,
});

function submit() {
    form.put(`/categories/${props.category.id}`);
}

function destroy() {
    if (confirm('Tem certeza que deseja excluir esta categoria?')) {
        router.delete(`/categories/${props.category.id}`);
    }
}
</script>

<template>
    <form @submit.prevent="submit" class="px-4 py-4 space-y-5">

        <!-- Name -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
            <input type="text" v-model="form.name" required maxlength="100"
                   class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm
                          transition-all duration-200
                          focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:shadow-md">
            <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
        </div>

        <!-- Type -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
            <div class="flex gap-2">
                <label v-for="type in types" :key="type" class="flex-1 cursor-pointer">
                    <input type="radio" v-model="form.type" :value="type" class="peer hidden">
                    <div class="text-center py-2.5 rounded-xl border border-gray-300 text-sm font-medium cursor-pointer
                                transition-all duration-200
                                peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 peer-checked:shadow-md
                                active:scale-[0.97]">
                        {{ typeLabels[type] }}
                    </div>
                </label>
            </div>
        </div>

        <!-- Icon -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Ícone</label>
            <div class="grid grid-cols-5 gap-2">
                <button v-for="icon in icons" :key="icon.id" type="button"
                        @click="form.icon_id = icon.id"
                        :class="['flex items-center justify-center h-12 rounded-xl border text-2xl transition-all duration-200 active:scale-[0.95]',
                                 form.icon_id === icon.id
                                    ? 'ring-2 ring-blue-500 border-blue-500 shadow-md'
                                    : 'border-gray-200']">
                    {{ icon.icon }}
                </button>
            </div>
        </div>

        <!-- Color -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Cor</label>
            <div class="flex gap-2 flex-wrap">
                <button v-for="color in colors" :key="color" type="button"
                        @click="form.color = color"
                        :class="['w-10 h-10 rounded-full border-2 transition-all duration-200',
                                 form.color === color
                                    ? 'border-gray-800 ring-2 ring-offset-2 ring-gray-400'
                                    : 'border-transparent']"
                        :style="{ backgroundColor: color }">
                </button>
            </div>
        </div>

        <!-- Actions -->
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
                Excluir categoria
            </button>
        </div>
    </form>
</template>
