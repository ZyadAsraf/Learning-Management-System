<!-- resources/js/Pages/Teacher/AssignmentIndex.vue -->
<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-2xl font-semibold">My Assignments</h2>
    </template>

    <div class="mt-6">
      <Link
        :href="route('teacher.assignments.create')"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow"
      >
        Create Assignment
      </Link>

      <table class="w-full mt-6 border">
        <thead class="bg-gray-100">
          <tr>
            <th class="border px-4 py-2">Title</th>
            <th class="border px-4 py-2">Course</th>
            <th class="border px-4 py-2">Due Date</th>
            <th class="border px-4 py-2">PDF</th>
            <th class="border px-4 py-2">Submissions</th>
            <th class="border px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="assignment in assignments" :key="assignment.id">
            <td class="border px-4 py-2">{{ assignment.title }}</td>
            <td class="border px-4 py-2">{{ assignment.course.title }}</td>
            <td class="border px-4 py-2">{{ assignment.due_date }}</td>
            <td class="border px-4 py-2">
              <a
                v-if="assignment.pdf_path"
                :href="`/storage/${assignment.pdf_path}`"
                target="_blank"
                class="text-blue-600 underline"
              >
                View PDF
              </a>
            </td>
            <td class="border px-4 py-2">
              <div v-if="assignment.submissions.length === 0">
                No Submissions
              </div>
              <ul v-else>
                <li
                  v-for="sub in assignment.submissions"
                  :key="sub.id"
                  class="text-sm"
                >
                  {{ sub.student.name }} - Grade: {{ sub.grade || 'Pending' }}
                </li>
              </ul>
            </td>
            <td class="border px-4 py-2">
              <Link :href="route('teacher.assignments.edit', assignment.id)" class="text-yellow-600 hover:underline mr-2">Edit</Link>
              <Link :href="route('teacher.assignments.delete', assignment.id)" class="text-red-600 hover:underline">Delete</Link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import axios from 'axios'
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const assignments = ref([])

onMounted(async () => {
  const token = localStorage.getItem('token')
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
  const res = await axios.get('/api/teacher/courses') // Fetch teacher's courses with assignments
  assignments.value = res.data.flatMap(course =>
    course.assignments?.map(a => ({ ...a, course })) || []
  )
})
</script>
