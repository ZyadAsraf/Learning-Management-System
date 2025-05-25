<script setup>
import VueApexCharts from 'vue3-apexcharts';

// Chart options
const barOptions = {
    chart: { type: 'bar', height: 200, toolbar: { show: false } },
    xaxis: { categories: ['S', 'T', 'T', 'S', 'M', 'W', 'F', 'S'] },
    colors: ['#1976d2'],
};
const barSeries = [{ name: 'Survey', data: [70, 50, 80, 60, 30, 40, 60, 50] }];

const donutOptions = {
    chart: { type: 'donut', height: 200 },
    labels: ['Mail-Order Sales'],
    colors: ['#1976d2'],
};
const donutSeries = [20];

const areaOptions = {
    chart: { type: 'area', height: 200, toolbar: { show: false } },
    xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'] },
    colors: ['#1976d2'],
};
const areaSeries = [{ name: 'Survey', data: [30, 100, 40, 120, 80, 150, 100] }];
</script>

<template>
    <div class="mt-16 mb-24">
        <h2 class="text-2xl font-bold mb-6">Analytics Overview</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg p-6 shadow-lg border border-gray-200">
                <div class="font-semibold mb-4 text-gray-700">Weekly Activity</div>
                <VueApexCharts type="bar" height="200" :options="barOptions" :series="barSeries" />
            </div>
            <div class="bg-white rounded-lg p-6 shadow-lg border border-gray-200">
                <div class="font-semibold mb-4 text-gray-700">Course Distribution</div>
                <VueApexCharts type="donut" height="200" :options="donutOptions" :series="donutSeries" />
            </div>
            <div class="bg-white rounded-lg p-6 shadow-lg border border-gray-200">
                <div class="font-semibold mb-4 text-gray-700">Monthly Trends</div>
                <VueApexCharts type="area" height="200" :options="areaOptions" :series="areaSeries" />
            </div>
        </div>
    </div>
</template> 