<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'inline-flex flex-col items-center px-4 py-2 border-2 border-white rounded-xl text-sm font-medium leading-5 text-white bg-transparent focus:outline-none transition duration-150 ease-in-out relative'
        : 'inline-flex items-center px-4 py-2 border-2 border-transparent rounded-xl text-sm font-medium leading-5 text-gray-400 hover:text-white hover:border-white focus:outline-none transition duration-150 ease-in-out',
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
        <span v-if="props.active" class="block w-6 h-1 bg-white rounded mt-1"></span>
    </Link>
</template>
