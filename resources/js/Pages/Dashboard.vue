<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import HeroSection from '@/components/dashboard/HeroSection.vue';
import StatsSection from '@/components/dashboard/StatsSection.vue';
import AnalyticsSection from '@/components/dashboard/AnalyticsSection.vue';
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Dashboard" />

    <div class="py-0">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <HeroSection />
        <StatsSection />
        <AnalyticsSection />
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Custom styles */
</style>

