<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold mb-4">Courses Page</h1>
        <button
          @click="openModal('create', {})"
          class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow transition flex items-center gap-2"
        >
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
          Create Course
        </button>
      </div>
    </template>

    <div class="flex justify-end mb-4">
      <button
        @click="viewType = viewType === 'grid' ? 'list' : 'grid'"
        class="px-4 py-2 rounded bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium transition"
      >
        Switch to {{ viewType === 'grid' ? 'List' : 'Grid' }} View
      </button>
    </div>

    <div class="p-8">
      <div v-if="loading" class="text-gray-500">Loading courses...</div>

      <div v-else>
        <div v-if="courses.length === 0" class="text-gray-500">No courses found.</div>

        <!-- Grid View -->
        <div v-if="viewType === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="course in courses"
            :key="course.id"
            class="bg-white rounded-2xl shadow p-6 flex flex-col justify-between"
          >
            <h2 class="text-xl font-semibold mb-2">{{ course.title }}</h2>
            <p class="text-gray-600 mb-2 flex-grow">{{ course.description }}</p>
            <span class="text-sm text-gray-500 mb-1">Visibility: {{ course.is_public ? 'Public' : 'Private' }}</span>
            <div class="flex justify-between items-center mt-4">
              <span class="text-sm text-gray-500">Teacher: {{ course.teacher.name || 'N/A' }}</span>
              <div class="flex gap-2">
                <button @click="openModal('edit', course)" class="px-3 py-1 bg-yellow-100 hover:bg-yellow-200 text-yellow-600 rounded shadow">Edit</button>
                <button @click="openModal('delete', course)" class="px-3 py-1 bg-red-100 hover:bg-red-200 text-red-600 rounded shadow">Delete</button>
                <button @click="openModal('details', course)" class="px-3 py-1 bg-green-100 hover:bg-green-200 text-green-600 rounded shadow">Details</button>
              </div>
            </div>
          </div>
        </div>

        <!-- List View -->
        <div v-else class="space-y-4">
          <div
            v-for="course in courses"
            :key="course.id"
            class="bg-white rounded-2xl shadow p-6 flex items-center justify-between"
          >
            <div>
              <h2 class="text-xl font-semibold">{{ course.title }}</h2>
              <p class="text-gray-600">{{ course.description }}</p>
              <div class="text-sm text-gray-500">
                Teacher: {{ course.teacher?.name || 'N/A' }}<br />
                Visibility: {{ course.is_public ? 'Public' : 'Private' }}
              </div>
            </div>
            <div class="flex gap-2">
              <button @click="openModal('edit', course)" class="px-3 py-1 bg-yellow-100 hover:bg-yellow-200 text-yellow-600 rounded shadow">Edit</button>
              <button @click="openModal('delete', course)" class="px-3 py-1 bg-red-100 hover:bg-red-200 text-red-600 rounded shadow">Delete</button>
              <button @click="openModal('details', course)" class="px-3 py-1 bg-green-100 hover:bg-green-200 text-green-600 rounded shadow">Details</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <CourseActionModal
        :show="showModal"
        :mode="modalMode"
        :item="selectedCourse"
        @close="closeModal"
        @confirm="handleModalAction"
      />
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import CourseActionModal from '@/components/CourseActionModal.vue' // updated import

const courses = ref([])
const loading = ref(true)
const viewType = ref('grid')
const showModal = ref(false)
const modalMode = ref('edit')
const selectedCourse = ref({})

const loadCourses = async () => {
  loading.value = true
  try {
    const token = localStorage.getItem('token')
    if (token) axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    const response = await axios.get('/api/courses')
    courses.value = response.data
  } catch (error) {
    console.error('Failed to load courses', error)
  } finally {
    loading.value = false
  }
}

onMounted(loadCourses)

const openModal = (mode, course) => {
  modalMode.value = mode
  selectedCourse.value = { ...course }
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
}

const handleModalAction = async (action) => {
  try {
    const payload = {
      ...action.data,
      is_public: !!action.data.is_public, // Ensure Boolean
    }

    if (action.action === 'create') {
      await axios.post('/api/courses', payload)
    } else if (action.action === 'edit') {
      await axios.put(`/api/courses/${action.data.id}`, payload)
    } else if (action.action === 'delete') {
      await axios.delete(`/api/courses/${action.id}`)
    }

    loadCourses()
  } catch (error) {
    console.error('Failed to perform action', error)
    alert('Action failed, please try again.')
  }

  closeModal()
}
</script>
