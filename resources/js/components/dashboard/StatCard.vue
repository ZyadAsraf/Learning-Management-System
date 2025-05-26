<script setup>
defineProps({
    value: {
        type: String,
        required: true
    },
    icon: {
        type: String,
        required: true
    },
    label: {
        type: String,
        required: true
    }
});
</script>

<template>
    <div class="bg-white rounded-lg p-6 shadow-lg border border-gray-200">
        <div class="text-2xl font-bold text-indigo-600">{{ value }}</div>
        <div class="text-gray-600 flex items-center justify-center">
            <i :class="[icon, 'text-lg mr-1']"></i>{{ label }}
        </div>
    </div>
</template>