<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-2xl font-semibold">Delete Assignment</h2>
    </template>

    <div class="mt-6">
      <p>Are you sure you want to delete <strong>{{ assignment.title }}</strong>?</p>
      <div class="mt-4 flex gap-4">
        <button @click="confirmDelete" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
        <Link :href="route('teacher.assignments.index')" class="text-gray-600 hover:underline">Cancel</Link>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { usePage, router } from '@inertiajs/vue3'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const assignment = usePage().props.assignment

const confirmDelete = async () => {
  await axios.delete(`/api/assignments/${assignment.id}`)
  router.visit(route('teacher.assignments.index'))
}
</script>
