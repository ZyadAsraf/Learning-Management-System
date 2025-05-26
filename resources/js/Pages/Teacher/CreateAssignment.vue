<!-- resources/js/Pages/Teacher/CreateAssignment.vue -->
<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-2xl font-semibold">Create Assignment</h2>
    </template>

    <form @submit.prevent="submitForm" class="mt-6 space-y-4">
      <div>
        <label>Course</label>
        <select v-model="form.course_id" class="w-full border px-4 py-2">
          <option v-for="course in courses" :key="course.id" :value="course.id">{{ course.title }}</option>
        </select>
      </div>
      <div>
        <label>Title</label>
        <input type="text" v-model="form.title" class="w-full border px-4 py-2" />
      </div>
      <div>
        <label>Description</label>
        <textarea v-model="form.description" class="w-full border px-4 py-2"></textarea>
      </div>
      <div>
        <label>Due Date</label>
        <input type="date" v-model="form.due_date" class="w-full border px-4 py-2" />
      </div>
      <div>
        <label>PDF File</label>
        <input type="file" @change="handleFile" class="w-full" />
      </div>

      <button class="bg-blue-600 text-white px-4 py-2 rounded">Create</button>
    </form>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { router } from '@inertiajs/vue3'

const form = ref({
  course_id: null,
  title: '',
  description: '',
  due_date: '',
  pdf_file: null,
})

const courses = ref([])

onMounted(async () => {
  const token = localStorage.getItem('token')
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
  const res = await axios.get('/api/teacher/courses')
  courses.value = res.data
})

const handleFile = (e) => {
  form.value.pdf_file = e.target.files[0]
}

const submitForm = async () => {
  const data = new FormData()
  for (let key in form.value) {
    data.append(key, form.value[key])
  }

  await axios.post('/api/assignments', data)
  router.visit(route('teacher.assignments.index'))
}
</script>
