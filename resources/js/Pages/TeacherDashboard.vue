<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Teacher Dashboard" />

    <div class="py-8">
      <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="bg-white rounded-2xl shadow p-6 mb-8">
          <h1 class="text-3xl font-bold mb-2 text-gray-800">Teacher Dashboard</h1>
          <p class="text-gray-600">Welcome to your dashboard! Use the tools below to manage your teaching materials.</p>
        </div>

        <!-- Upload Section -->
        <div class="bg-white rounded-2xl shadow p-6 flex items-center justify-between">
          <div>
            <h2 class="text-xl font-semibold text-gray-800 mb-1">Upload Teaching Materials</h2>
            <p class="text-gray-600">Easily upload resources and share them with students.</p>
          </div>
          <Link
            href="/teacher/materials/upload"
            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition"
          >
            Upload New Material
          </Link>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Add custom styles here if needed */
</style>
