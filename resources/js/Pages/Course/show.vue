<template>
  <AuthenticatedLayout>
    <template #header>
      <h1 class="text-3xl font-bold mb-4">Course Details</h1>
    </template>

    <div class="bg-white shadow rounded-2xl p-8 max-w-2xl mx-auto text-gray-800">
      <div class="mb-4">
        <h2 class="text-xl font-semibold text-blue-600">Title</h2>
        <p class="text-lg">{{ course.title }}</p>
      </div>

      <div class="mb-4">
        <h2 class="text-xl font-semibold text-blue-600">Description</h2>
        <p class="text-gray-700">{{ course.description }}</p>
      </div>

      <div class="mb-4">
        <h2 class="text-xl font-semibold text-blue-600">Teacher</h2>
        <p>{{ course.teacher?.name || 'N/A' }}</p>
      </div>

      <div class="mb-4">
        <h2 class="text-xl font-semibold text-blue-600">Visibility</h2>
        <p>{{ course.is_public ? 'Public' : 'Private' }}</p>
      </div>

      <div class="mt-6">
        <button @click="$router.back()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg shadow">
          Back
        </button>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const course = ref({})
const courseId = usePage().props.courseId

onMounted(async () => {
  try {
    const response = await axios.get(`/api/courses/${courseId}`)
    course.value = response.data
  } catch (error) {
    alert('Failed to load course details')
    console.error(error)
  }
})
</script>
