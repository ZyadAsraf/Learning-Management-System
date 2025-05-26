<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-2xl font-semibold">Create Grading Submission</h2>
    </template>

    <div class="max-w-3xl mx-auto mt-6 bg-white p-6 rounded shadow">
      <form @submit.prevent="submitForm">
        <!-- Assignment Select -->
        <div class="mb-4">
          <label for="assignment_id" class="block font-medium mb-1">Assignment</label>
          <select
            id="assignment_id"
            v-model="form.assignment_id"
            class="w-full border rounded px-3 py-2"
            required
          >
            <option value="" disabled selected>Select Assignment</option>
            <option v-for="a in assignments" :key="a.assignment_id" :value="a.assignment_id">
              {{ a.title }}
            </option>
          </select>
        </div>

        <!-- Student Select -->
        <div class="mb-4">
          <label for="student_id" class="block font-medium mb-1">Student</label>
          <select
            id="student_id"
            v-model="form.student_id"
            class="w-full border rounded px-3 py-2"
            required
          >
            <option value="" disabled selected>Select Student</option>
            <option v-for="s in students" :key="s.student_id" :value="s.student_id">
              {{ s.name }}
            </option>
          </select>
        </div>

        <div class="mb-4">
          <label for="content" class="block font-medium mb-1">Content</label>
          <textarea
            id="content"
            v-model="form.content"
            rows="4"
            class="w-full border rounded px-3 py-2"
          ></textarea>
        </div>

        <div class="mb-4">
          <label for="pdf_path" class="block font-medium mb-1">PDF Path</label>
          <input
            type="text"
            id="pdf_path"
            v-model="form.pdf_path"
            class="w-full border rounded px-3 py-2"
            placeholder="e.g. submissions/file.pdf"
          />
        </div>

        <div class="mb-4">
          <label for="grade" class="block font-medium mb-1">Grade</label>
          <input
            type="text"
            id="grade"
            v-model="form.grade"
            class="w-full border rounded px-3 py-2"
            placeholder="e.g. A, 90, 85.5"
          />
        </div>

        <div class="mb-4">
          <label for="feedback" class="block font-medium mb-1">Feedback</label>
          <textarea
            id="feedback"
            v-model="form.feedback"
            rows="3"
            class="w-full border rounded px-3 py-2"
          ></textarea>
        </div>

        <div class="flex justify-end">
          <button
            type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded"
            :disabled="loading"
          >
            {{ loading ? 'Saving...' : 'Save' }}
          </button>
        </div>
      </form>

      <div v-if="error" class="mt-4 text-red-600 font-semibold">
        {{ error }}
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const form = ref({
  assignment_id: '',
  student_id: '',
  content: '',
  pdf_path: '',
  grade: '',
  feedback: '',
})

const error = ref('')
const loading = ref(false)
const assignments = ref([])
const students = ref([])

onMounted(async () => {
  const token = localStorage.getItem('token')
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

  try {
    const [assignmentsRes, studentsRes] = await Promise.all([
      axios.get('/api/teacher/assignments'),
      axios.get('/api/students'),
    ])
    assignments.value = assignmentsRes.data
    students.value = studentsRes.data
  } catch (err) {
    error.value = 'Failed to load assignments or students.'
  }
})

const submitForm = async () => {
  loading.value = true
  error.value = ''
  try {
    const token = localStorage.getItem('token')
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    await axios.post('/api/grading-submissions', form.value)
    window.location.href = '/teacher/grading-submissions'
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to save grading submission.'
  } finally {
    loading.value = false
  }
}
</script>
