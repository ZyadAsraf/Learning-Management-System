<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold">Courses</h1>
        <button
          @click="goTo('create')"
          class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow flex items-center gap-2"
        >
          <span>+ Create Course</span>
        </button>
      </div>
    </template>

    <div class="p-6">
      <div v-if="loading" class="text-gray-500">Loading courses...</div>
      <div v-else>
        <div v-if="courses.length === 0" class="text-gray-500">No courses found.</div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="course in courses"
            :key="course.id"
            class="bg-white p-6 rounded-2xl shadow flex flex-col justify-between"
          >
            <h2 class="text-xl font-semibold mb-2">{{ course.title }}</h2>
            <p class="text-gray-600 mb-2">{{ course.description }}</p>
            <span class="text-sm text-gray-500 mb-1">Visibility: {{ course.is_public ? 'Public' : 'Private' }}</span>
            <div class="text-sm text-gray-500">Teacher: {{ course.teacher?.name || 'N/A' }}</div>

            <div class="flex justify-end gap-2 mt-4">
              <button @click="goTo('edit', course.id)" class="px-3 py-1 bg-yellow-100 hover:bg-yellow-200 text-yellow-600 rounded">Edit</button>
              <button @click="goTo('delete', course.id)" class="px-3 py-1 bg-red-100 hover:bg-red-200 text-red-600 rounded">Delete</button>
              <button @click="goTo('show', course.id)" class="px-3 py-1 bg-green-100 hover:bg-green-200 text-green-600 rounded">Details</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3' // ✅ Use Inertia router, NOT vue-router
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const courses = ref([])
const loading = ref(true)

const loadCourses = async () => {
  loading.value = true
  try {
    const token = localStorage.getItem('token')
    if (token) axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    const response = await axios.get('/api/courses')
    courses.value = response.data
  } catch (error) {
    console.error('Error fetching courses', error)
  } finally {
    loading.value = false
  }
}

const goTo = (action, id = null) => {
  if (action === 'create') {
    router.visit('/courses/create')
  } else if (action === 'edit') {
    router.visit(`/courses/${id}/edit`)
  } else if (action === 'delete') {
    router.visit(`/courses/${id}/delete`) // You may need to handle this as a modal or confirmation
  } else if (action === 'show') {
    router.visit(`/courses/${id}`)
  }
}

onMounted(loadCourses)
</script>
