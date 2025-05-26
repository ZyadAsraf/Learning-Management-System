<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

// Example course data - you can replace this with actual data from your backend
const courses = [
  {
    id: 1,
    title: 'Introduction to Computer Science',
    instructor: 'Dr. Smith',
    progress: 75,
    thumbnail: 'https://lms.gkce.edu.in/wp-content/uploads/2025/01/computer-science-and-engine.jpg',
    lastAccessed: '2 days ago'
  },
  {
    id: 2,
    title: 'Web Development Fundamentals',
    instructor: 'Prof. Johnson',
    progress: 45,
    thumbnail: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQOiBSrY3rPB6EYrh3FaDqXejdrHY_MZKMmPIJ0IKPsuyaIWBZdqJkJItFZ9ndL3i6HTBI&usqp=CAU',
    lastAccessed: '1 day ago'
  },
  {
    id: 3,
    title: 'Data Structures and Algorithms',
    instructor: 'Dr. Williams',
    progress: 30,
    thumbnail: 'https://images.squarespace-cdn.com/content/v1/5e0a76ff40ec75769594bc73/1607665765233-P9TXJ9E0PWM3KXMHFV6O/main-qimg-b1362c2db3d23c0fafa8623aa53fed75.png',
    lastAccessed: '3 days ago'
  }
];
</script>

<template>
  <Head title="My Courses" />
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gray-900 text-gray-100">
      <div class="p-8">
        <div class="flex justify-between items-center mb-8">
          <h1 class="text-2xl font-bold">My Courses</h1>
          <div class="flex gap-4">
            <input 
              type="text" 
              placeholder="Search courses..." 
              class="border rounded px-3 py-1 bg-gray-800 text-gray-100 border-gray-700"
            />
            <button class="bg-indigo-600 hover:bg-indigo-700 px-4 py-1 rounded">
              Filter
            </button>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="course in courses" :key="course.id" 
               class="bg-gray-800 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow">
            <img :src="course.thumbnail" :alt="course.title" class="w-full h-48 object-cover" />
            <div class="p-4">
              <h3 class="text-xl font-semibold mb-2">{{ course.title }}</h3>
              <p class="text-gray-400 mb-2">Instructor: {{ course.instructor }}</p>
              <div class="mb-2">
                <div class="flex justify-between text-sm mb-1">
                  <span>Progress</span>
                  <span>{{ course.progress }}%</span>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-2">
                  <div :style="{ width: course.progress + '%' }" 
                       class="bg-indigo-600 h-2 rounded-full"></div>
                </div>
              </div>
              <div class="flex justify-between items-center mt-4">
                <span class="text-sm text-gray-400">Last accessed: {{ course.lastAccessed }}</span>
                <button class="bg-indigo-600 hover:bg-indigo-700 px-4 py-1 rounded text-sm">
                  Continue Learning
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template> 