<template>
  <AuthenticatedLayout>
    <template #header>
  <div class="flex items-center justify-between">
    <h2 class="text-3xl font-bold text-gray-800">Grading Submissions</h2>
    <Link
      :href="route('teacher.grading-submissions.create')"
      class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg shadow transition duration-200"
    >
      + New Submission
    </Link>
  </div>
</template>


    <div class="mt-8 space-y-6">
    

      <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full table-auto">
          <thead class="bg-gray-100 text-gray-700 text-left text-sm uppercase tracking-wider">
            <tr>
              <th class="px-6 py-3 border-b">Assignment</th>
              <th class="px-6 py-3 border-b">Student</th>
              <th class="px-6 py-3 border-b">Content</th>
              <th class="px-6 py-3 border-b">PDF</th>
              <th class="px-6 py-3 border-b">Grade</th>
              <th class="px-6 py-3 border-b">Feedback</th>
              <th class="px-6 py-3 border-b text-center">Actions</th>
            </tr>
          </thead>
          <tbody class="text-gray-800">
            <tr
              v-for="submission in gradingSubmissions"
              :key="submission.id"
              class="hover:bg-gray-50 transition"
            >
              <td class="px-6 py-4 border-b">{{ submission.assignment_id }}</td>
              <td class="px-6 py-4 border-b">{{ submission.student_id }}</td>
              <td class="px-6 py-4 border-b">{{ submission.content }}</td>
              <td class="px-6 py-4 border-b">
                <a
                  v-if="submission.pdf_path"
                  :href="`/storage/${submission.pdf_path}`"
                  target="_blank"
                  class="text-blue-600 hover:underline"
                >
                  View PDF
                </a>
              </td>
              <td class="px-6 py-4 border-b">{{ submission.grade || 'Pending' }}</td>
              <td class="px-6 py-4 border-b">{{ submission.feedback || '—' }}</td>
              <td class="px-6 py-4 border-b text-center space-x-2">
                <Link
                  :href="route('teacher.grading-submissions.edit', submission.id)"
                  class="text-yellow-600 hover:text-yellow-800 font-medium"
                >
                  Edit
                </Link>
                <Link
                  :href="route('teacher.grading-submissions.delete', submission.id)"
                  class="text-red-600 hover:text-red-800 font-medium"
                >
                  Delete
                </Link>
              </td>
            </tr>
            <tr v-if="gradingSubmissions.length === 0">
              <td colspan="7" class="text-center py-6 text-gray-500">
                No submissions found.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import axios from 'axios'
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const gradingSubmissions = ref([])

onMounted(async () => {
  const token = localStorage.getItem('token')
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
  const res = await axios.get('/api/teacher/grading-submissions')
  gradingSubmissions.value = res.data
})
</script>
