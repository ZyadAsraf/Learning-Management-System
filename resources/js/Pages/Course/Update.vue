<template>
  <AuthenticatedLayout>
    <template #header>
      <h1 class="text-3xl font-bold mb-4">Edit Course</h1>
    </template>

    <div class="bg-white shadow rounded-2xl p-8 max-w-2xl mx-auto">
      <form @submit.prevent="submitForm">
        <div class="mb-4">
          <label class="block font-medium mb-1">Title</label>
          <input v-model="form.title" type="text" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required />
        </div>

        <div class="mb-4">
          <label class="block font-medium mb-1">Description</label>
          <textarea v-model="form.description" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>

        <div class="mb-4">
          <label class="block font-medium mb-1">Teacher ID</label>
          <input v-model="form.teacher_id" type="number" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required />
        </div>

        <div class="mb-6">
          <label class="inline-flex items-center gap-2">
            <input v-model="form.is_public" type="checkbox" class="rounded" />
            <span class="text-sm text-gray-700">Make Public</span>
          </label>
        </div>

        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg shadow font-medium">
          Update Course
        </button>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { router, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const courseId = usePage().props.courseId
const form = ref({
  title: '',
  description: '',
  teacher_id: '',
  is_public: false,
})

onMounted(async () => {
  try {
    const response = await axios.get(`/api/courses/${courseId}`)
    Object.assign(form.value, response.data)
  } catch (error) {
    alert('Failed to load course')
    console.error(error)
  }
})

const submitForm = async () => {
  try {
    await axios.put(`/api/courses/${courseId}`, form.value)
    router.visit('/courses')
  } catch (error) {
    alert('Failed to update course')
    console.error(error)
  }
}
</script>
