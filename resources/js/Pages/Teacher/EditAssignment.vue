<!-- resources/js/Pages/Teacher/EditAssignment.vue -->
<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-2xl font-semibold">Edit Assignment</h2>
    </template>

    <form @submit.prevent="submitForm" class="mt-6 space-y-4">
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
        <label>Replace PDF (optional)</label>
        <input type="file" @change="handleFile" class="w-full" />
      </div>

      <button class="bg-yellow-600 text-white px-4 py-2 rounded">Update</button>
    </form>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const assignment = usePage().props.assignment
const form = ref({
  title: assignment.title,
  description: assignment.description,
  due_date: assignment.due_date,
  pdf_file: null,
})

const handleFile = (e) => {
  form.value.pdf_file = e.target.files[0]
}

const submitForm = async () => {
  const data = new FormData()
  for (let key in form.value) {
    data.append(key, form.value[key])
  }

  await axios.post(`/api/assignments/${assignment.id}?_method=PUT`, data)
  router.visit(route('teacher.assignments.index'))
}
</script>
