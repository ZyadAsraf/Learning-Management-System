<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-2xl font-semibold text-red-600">Delete Grading Submission</h2>
    </template>

    <div class="max-w-xl mx-auto mt-6 bg-white p-6 rounded shadow">
      <p class="mb-4">
        Are you sure you want to delete this grading submission with ID <strong>{{ id }}</strong>?
      </p>

      <div class="flex space-x-4">
        <button
          @click="deleteSubmission"
          class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded"
          :disabled="loading"
        >
          {{ loading ? 'Deleting...' : 'Yes, Delete' }}
        </button>

        <button
          @click="cancel"
          class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded"
          :disabled="loading"
        >
          Cancel
        </button>
      </div>

      <div v-if="error" class="mt-4 text-red-600 font-semibold">
        {{ error }}
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  id: {
    type: [Number, String],
    required: true,
  },
})

const loading = ref(false)
const error = ref('')
const router = useRouter()

const deleteSubmission = async () => {
  loading.value = true
  error.value = ''
  try {
    const token = localStorage.getItem('token')
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    await axios.delete(`/api/grading-submissions/${props.id}`)

    // Redirect back to grading submissions list after delete
    window.location.href = '/teacher/grading-submissions'
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to delete grading submission.'
  } finally {
    loading.value = false
  }
}

const cancel = () => {
  window.history.back()
}
</script>
