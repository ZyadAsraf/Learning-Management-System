<template>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Courses</h1>
            <Link
              v-if="$page.props.auth.user.role === 'teacher'"
              :href="route('courses.create')"
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
            >
              Create New Course
            </Link>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
              v-for="course in courses"
              :key="course.id"
              class="border rounded-lg p-4 hover:shadow-lg transition-shadow"
            >
              <h2 class="text-xl font-semibold mb-2">{{ course.title }}</h2>
              <p class="text-gray-600 mb-4">{{ course.description }}</p>
              <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500">
                  Teacher: {{ course.teacher.name }}
                </span>
                <Link
                  :href="route('courses.show', course.id)"
                  class="text-blue-500 hover:text-blue-700"
                >
                  View Course
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  courses: {
    type: Array,
    required: true
  }
})
</script> 