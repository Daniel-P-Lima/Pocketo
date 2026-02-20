<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    link: String 
})
</script>

<template>
    <Link :href="link"
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