<script setup>
import { computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import BottomNav from '../Components/BottomNav.vue';
import FlashMessage from '../Components/FlashMessage.vue';

const page = usePage();

const header = computed(() => page.props.header ?? null);
const backUrl = computed(() => page.props.backUrl ?? null);
</script>

<template>
    <div class="min-h-screen pb-20">
        <header v-if="header" class="bg-white border-b border-gray-200 px-4 py-3 flex items-center">
            <Link v-if="backUrl" :href="backUrl" class="mr-3 p-1 -ml-1 transition-transform duration-150 active:scale-90">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </Link>
            <h1 class="text-lg font-semibold">{{ header }}</h1>
        </header>

        <main>
            <FlashMessage />
            <Transition name="page" mode="out-in" appear>
                <div :key="page.url">
                    <slot />
                </div>
            </Transition>
        </main>
    </div>

    <BottomNav />
</template>

<style>
.page-enter-active {
    transition: opacity 0.2s ease, transform 0.2s ease;
}
.page-leave-active {
    transition: opacity 0.15s ease;
}
.page-enter-from {
    opacity: 0;
    transform: translateY(8px);
}
.page-leave-to {
    opacity: 0;
}
</style>
