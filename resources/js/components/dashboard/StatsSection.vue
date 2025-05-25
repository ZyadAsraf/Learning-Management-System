<script setup>
import { ref } from 'vue';
import StatCard from '@/components/dashboard/StatCard.vue';
// Stats data
const stats = ref([
    {
        value: '316,000+',
        icon: 'bi-mortarboard-fill',
        label: 'Expert Educators'
    },
    {
        value: '1.8 Billion+',
        icon: 'bi-people-fill',
        label: 'Active Learners'
    },
    {
        value: '41,000+',
        icon: 'bi-book-fill',
        label: 'Learning Resources'
    },
    {
        value: '179,000+',
        icon: 'bi-camera-video-fill',
        label: 'Learning Hours'
    }
]);
</script>

<template>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 mt-16 text-center">
        <StatCard
            v-for="(stat, index) in stats"
            :key="index"
            :value="stat.value"
            :icon="stat.icon"
            :label="stat.label"
        />
    </div>
</template>