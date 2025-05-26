<script setup>
import { ref, computed } from 'vue';
import VueApexCharts from 'vue3-apexcharts';
import ChartCard from '@/components/dashboard/ChartCard.vue';
// Chart data using reactive state
const chartData = ref({
    title: 'Analytics Overview',
    charts: [
        {
            title: 'Weekly Activity',
            type: 'bar',
            options: {
                chart: { type: 'bar', height: 200, toolbar: { show: false } },
                xaxis: { categories: ['S', 'T', 'T', 'S', 'M', 'W', 'F', 'S'] },
                colors: ['#1976d2'],
            },
            series: [{ name: 'Survey', data: [70, 50, 80, 60, 30, 40, 60, 50] }]
        },
        {
            title: 'Course Distribution',
            type: 'donut',
            options: {
                chart: { type: 'donut', height: 200 },
                labels: ['Mail-Order Sales'],
                colors: ['#1976d2'],
            },
            series: [20]
        },
        {
            title: 'Monthly Trends',
            type: 'area',
            options: {
                chart: { type: 'area', height: 200, toolbar: { show: false } },
                xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'] },
                colors: ['#1976d2'],
            },
            series: [{ name: 'Survey', data: [30, 100, 40, 120, 80, 150, 100] }]
        }
    ]
});
</script>

<template>
    <div class="mt-16 mb-24">
        <h2 class="text-2xl font-bold mb-6">{{ chartData.title }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <ChartCard 
                v-for="(chart, index) in chartData.charts" 
                :key="index"
                :title="chart.title"
                :type="chart.type"
                :options="chart.options"
                :series="chart.series"
            />
        </div>
    </div>
</template>