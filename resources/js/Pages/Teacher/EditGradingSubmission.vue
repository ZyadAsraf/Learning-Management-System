<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-2xl font-semibold">Edit Submission</h2>
    </template>

    <div class="mt-6">
      <form @submit.prevent="updateSubmission">
        <div>
          <label>Grade:</label>
          <input v-model="form.grade" type="number" class="border rounded p-2 w-full" />
        </div>
        <div class="mt-4">
          <label>Feedback:</label>
          <textarea v-model="form.feedback" class="border rounded p-2 w-full" />
        </div>
        <button type="submit" class="mt-4 bg-green-600 text-white px-4 py-2 rounded">Update</button>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const route = useRoute()
const router = useRouter()
const form = ref({ grade: '', feedback: '' })

onMounted(async () => {
  const token = localStorage.getItem('token')
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
  const res = await axios.get(`/api/teacher/grading-submissions/${route.params.id}`)
  form.value = res.data
})

const updateSubmission = async () => {
  await axios.put(`/api/teacher/grading-submissions/${route.params.id}`, form.value)
  router.push({ name: 'teacher.grading-submissions.index' })
}
</script>
