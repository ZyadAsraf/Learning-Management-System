<script setup>
defineEmits(['toggle']);
</script>

<template>
    <button 
        @click="$emit('toggle')"
        class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-indigo-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 transition-colors"
    >
        <i class="bi bi-list text-xl"></i>
    </button>
</template>