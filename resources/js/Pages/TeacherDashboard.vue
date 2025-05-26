<!-- resources/js/Pages/TeacherDashboard.vue -->
<template>
    <div class="p-6 max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Teacher Dashboard</h1>
        <p class="mb-6 text-gray-700">Welcome to your dashboard!</p>

        <Link href="/teacher/materials/upload"
            class="inline-block bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
        Upload New Material
        </Link>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<style scoped></style>
