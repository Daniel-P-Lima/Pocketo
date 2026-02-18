<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const success = computed(() => page.props.flash?.success);
const error = computed(() => page.props.flash?.error);
</script>

<template>
    <div v-if="success" class="mx-4 mt-4 bg-emerald-50 text-emerald-700 text-sm px-4 py-3 rounded-xl">
        {{ success }}
    </div>
    <div v-if="error" class="mx-4 mt-4 bg-red-50 text-red-700 text-sm px-4 py-3 rounded-xl">
        {{ error }}
    </div>
</template>
