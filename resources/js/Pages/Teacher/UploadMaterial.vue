<template>
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gray-50">
      <!-- Header -->
      <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center py-6">
            <h1 class="text-3xl font-bold text-gray-900">Upload Materials</h1>
            <div class="text-sm text-gray-600">Welcome, {{ teacherName }}</div>
          </div>
        </div>
      </div>

      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Loading State -->
        <div v-if="loading" class="text-center text-gray-500 py-10 animate-pulse">Loading data...</div>

        <!-- Error State -->
        <div v-else-if="error" class="text-center text-red-600 py-6">
          <p class="mb-2 font-medium">Failed to load data</p>
          <p class="text-sm">{{ error }}</p>
          <button
            @click="init"
            class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
          >
            Retry
          </button>
        </div>

        <!-- Form -->
        <div v-else class="bg-white p-6 rounded-xl shadow-md space-y-6">
          <form @submit.prevent="handleSubmit" enctype="multipart/form-data" class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-gray-700">Select Course</label>
              <select
                v-model="form.course_id"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
              >
                <option value="" disabled>Select Course</option>
                <option v-for="course in courses" :key="course.id" :value="course.id">
                  {{ course.title }}
                </option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Material Title</label>
              <input
                v-model="form.title"
                type="text"
                required
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Content</label>
              <textarea
                v-model="form.content"
                rows="4"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
              ></textarea>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Upload File (PDF or Video)</label>
              <input
                type="file"
                accept="application/pdf,video/*"
                @change="handleFileChange"
                class="mt-1 block w-full text-sm text-gray-500 file:bg-blue-600 file:text-white file:px-4 file:py-2 file:rounded"
              />
            </div>

            <div class="flex justify-end">
              <button
                type="submit"
                :disabled="isSubmitting"
                class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50"
              >
                {{ isSubmitting ? 'Uploading...' : 'Upload Material' }}
              </button>
            </div>
          </form>
        </div>

        <!-- Uploaded Materials -->
        <div v-if="materials.length" class="mt-10 bg-white p-6 rounded-xl shadow-sm">
          <h2 class="text-xl font-bold mb-4 text-gray-900">Uploaded Materials</h2>
          <ul class="list-disc list-inside space-y-2 text-gray-700">
            <li v-for="material in materials" :key="material.id">
              <span class="font-semibold">{{ material.title }}</span> — {{ material.file_type || 'Unknown type' }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const form = ref({
  course_id: '',
  title: '',
  content: '',
  file: null
});

const isSubmitting = ref(false);
const loading = ref(true);
const error = ref(null);
const courses = ref([]);
const materials = ref([]);
const teacherName = ref('Teacher');

// 🔐 Get token from localStorage
const token = localStorage.getItem('token');

const authHeaders = {
  headers: {
    Authorization: `Bearer ${token}`
  }
};

const init = async () => {
  loading.value = true;
  error.value = null;

  if (!token) {
    error.value = 'Authentication token not found. Please log in again.';
    loading.value = false;
    return;
  }

  try {
    const [coursesRes, materialsRes] = await Promise.all([
      axios.get('/api/teacher/courses', authHeaders),
      axios.get('/api/materials', authHeaders)
    ]);

    courses.value = coursesRes.data || [];
    materials.value = materialsRes.data || [];
    teacherName.value = 'Instructor'; // Update if teacher name is returned
  } catch (err) {
    console.error(err);
    error.value = err.response?.data?.message || 'Failed to fetch data';
  } finally {
    loading.value = false;
  }
};

const handleFileChange = (e) => {
  form.value.file = e.target.files[0];
};

const handleSubmit = async () => {
  if (!token) {
    alert('Authentication token missing. Please log in.');
    return;
  }

  isSubmitting.value = true;

  try {
    const payload = new FormData();
    payload.append('course_id', form.value.course_id);
    payload.append('title', form.value.title);
    payload.append('content', form.value.content || '');
    if (form.value.file) payload.append('file', form.value.file);

    const res = await axios.post('/api/materials', payload, {
      headers: {
        'Content-Type': 'multipart/form-data',
        Authorization: `Bearer ${token}`
      }
    });

    materials.value.push(res.data);
    alert('Material uploaded successfully!');
    form.value = { course_id: '', title: '', content: '', file: null };
  } catch (err) {
    console.error('Upload error', err);
    alert('Upload failed. Please check your input.');
  } finally {
    isSubmitting.value = false;
  }
};

onMounted(() => {
  init();
});
</script>


<style scoped>
.animate-pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
</style>
