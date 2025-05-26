<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    href: {
        type: [String, Object],
        required: true
    }
});
</script>

<template>
    <Link 
        :href="href" 
        class="flex items-center gap-3 text-gray-700 hover:bg-indigo-50 p-4 rounded-lg transition-colors"
    >
        <slot></slot>
    </Link>
</template>