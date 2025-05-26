<script setup>
import VueApexCharts from 'vue3-apexcharts';

defineProps({
    title: {
        type: String,
        required: true
    },
    type: {
        type: String,
        required: true
    },
    options: {
        type: Object,
        required: true
    },
    series: {
        type: [Array, Number],
        required: true
    }
});
</script>

<template>
    <div class="bg-white rounded-lg p-6 shadow-lg border border-gray-200">
        <div class="font-semibold mb-4 text-gray-700">{{ title }}</div>
        <VueApexCharts :type="type" height="200" :options="options" :series="series" />
    </div>
</template>