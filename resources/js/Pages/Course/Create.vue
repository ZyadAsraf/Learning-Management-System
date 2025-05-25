<template>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <h1 class="text-2xl font-semibold mb-6">Create New Course</h1>

          <form @submit.prevent="submit" class="space-y-6">
            <div>
              <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
              <input
                id="title"
                v-model="form.title"
                type="text"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required
              />
              <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">
                {{ form.errors.title }}
              </div>
            </div>

            <div>
              <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
              <textarea
                id="description"
                v-model="form.description"
                rows="4"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required
              ></textarea>
              <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">
                {{ form.errors.description }}
              </div>
            </div>

            <div class="flex items-center justify-end space-x-4">
              <Link
                :href="route('courses.index')"
                class="text-gray-600 hover:text-gray-900"
              >
                Cancel
              </Link>
              <button
                type="submit"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                :disabled="form.processing"
              >
                Create Course
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'

const form = useForm({
  title: '',
  description: ''
})

const submit = () => {
  form.post(route('courses.store'))
}
</script> 