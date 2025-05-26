<template>
  <div class="course-page p-8 bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto">
      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Course Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-6">
          <h1 class="text-3xl font-bold">{{ course.title || 'Loading...' }}</h1>
        </div>

        <!-- Loading or Error State -->
        <div v-if="loading || error" class="p-6">
          <div
            class="bg-white rounded-lg shadow-md p-6 text-center"
            :class="{ 'border-t-4 border-blue-600': loading, 'border-t-4 border-red-500': error }"
          >
            <div v-if="loading" class="text-white">
              <h2 class="text-xl font-semibold">Loading...</h2>
            </div>
            <div v-else-if="error" class="text-gray-900">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-12 h-12 mx-auto mb-4 text-red-300"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"
                />
              </svg>
              <p class="text-lg text-red-600">{{ error }}</p>
            </div>
          </div>
        </div>

        <!-- Content -->
        <div v-if="!loading && !error">
          <!-- Materials Section -->
          <section class="p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Materials</h2>
            <div v-if="materials.length === 0" class="text-gray-500 text-center py-4">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-12 h-12 mx-auto mb-3 text-gray-300"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
              <p>No materials available for this course.</p>
            </div>
            <ul v-else class="space-y-4">
              <li
                v-for="material in materials"
                :key="material.title"
                class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
              >
                <span class="text-sm font-medium text-gray-900">{{ material.title }}</span>
                <button
                  @click="download(material.pdf_path)"
                  class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition flex items-center gap-2"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-4 h-4"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"
                    />
                  </svg>
                  Download PDF
                </button>
              </li>
            </ul>
          </section>

          <!-- Assignments Section -->
          <section class="p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Assignments</h2>
            <div v-if="assignments.length === 0" class="text-gray-500 text-center py-4">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-12 h-12 mx-auto mb-3 text-gray-300"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
              <p>No assignments available for this course.</p>
            </div>
            <ul v-else class="space-y-4">
              <li
                v-for="assignment in assignments"
                :key="assignment.title"
                class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
              >
                <div>
                  <strong class="text-sm font-medium text-gray-900">{{ assignment.title }}</strong>
                  <span class="text-sm text-gray-500 block">Due: {{ formatDate(assignment.due_date) }}</span>
                </div>
                <button
                  @click="download(assignment.pdf_path)"
                  class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition flex items-center gap-2"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-4 h-4"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"
                    />
                  </svg>
                  Download PDF
                </button>
              </li>
            </ul>
          </section>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'CourseContent',
  props: {
    course_id: {
      type: [String, Number],
      required: true,
    },
  },
  data() {
    return {
      course: {
        title: '',
      },
      materials: [],
      assignments: [],
      loading: true,
      error: null,
    };
  },
  mounted() {
    this.fetchCourseData();
  },
  methods: {
    async fetchCourseData() {
      this.loading = true;
      this.error = null;
      try {
        const token = localStorage.getItem('token');
        if (token) axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        
        // Use the passed course_id prop here
        const response = await axios.get(`/api/student/${this.course_id}/course-content`);
        const data = response.data;

        this.course.title = data.course_title;
        this.materials = data.materials.map((m) => ({
          title: m.title,
          pdf_path: m.pdf_path,
        }));
        this.assignments = data.assignments.map((a) => ({
          title: a.title,
          pdf_path: a.pdf_path,
          due_date: a.due_date,
        }));
      } catch (error) {
        console.error('Error loading course data:', error);
        if (error.response) {
          if (error.response.status === 403) {
            this.error = 'You are not authorized to view this course.';
          } else if (error.response.status === 404) {
            this.error = 'Course not found.';
          } else {
            this.error = error.response.data.error || 'Failed to connect to the server. Please try again.';
          }
        } else {
          this.error = 'Failed to connect to the server. Please try again.';
        }
      } finally {
        this.loading = false;
      }
    },
    download(path) {
      if (!path) {
        alert('Invalid PDF path.');
        return;
      }
      window.open(path, '_blank');
    },
    formatDate(date) {
      if (!date) return 'No due date';
      try {
        return new Date(date).toLocaleDateString('en-US', {
          year: 'numeric',
          month: 'long',
          day: 'numeric',
        });
      } catch (e) {
        return 'Invalid date';
      }
    },
  },
};

</script>