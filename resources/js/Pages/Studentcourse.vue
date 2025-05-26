<!-- CourseContent.vue -->
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
            <div v-if="loading" class="text-gray-900">
              <div class="flex justify-center mb-4">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
              </div>
              <h2 class="text-xl font-semibold">Loading...</h2>
            </div>
            <div v-else-if="error" class="text-gray-900">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-12 h-12 mx-auto mb-4 text-red-500"
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
          <section class="p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Materials</h2>
            <div v-if="materials.length === 0" class="text-gray-500 text-center py-8">
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
                  d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"
                />
              </svg>
              <p>No materials available for this course.</p>
            </div>
            <ul v-else class="space-y-3">
              <li
                v-for="material in materials"
                :key="material.id || material.title"
                class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer"
                @click="viewMaterial(material.id)"
              >
                <div class="flex items-center gap-3">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-5 h-5 text-gray-500"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"
                    />
                  </svg>
                  <span class="text-sm font-medium text-gray-900">{{ material.title }}</span>
                </div>
                <button
                  @click.stop="goToContentMaterial(material.id)"
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
            <div v-if="assignments.length === 0" class="text-gray-500 text-center py-8">
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
                  d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h4.125M8.25 8.25V6.108"
                />
              </svg>
              <p>No assignments available for this course.</p>
            </div>
            <ul v-else class="space-y-3">
              <li
                v-for="assignment in assignments"
                :key="assignment.id"
                class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer"
                @click="viewAssignment(assignment.id)"
              >
                <div class="flex items-center gap-3 flex-1">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-5 h-5 text-gray-500 flex-shrink-0"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h4.125M8.25 8.25V6.108"
                    />
                  </svg>
                  <div class="min-w-0 flex-1">
                    <div class="text-sm font-medium text-gray-900 truncate">{{ assignment.title }}</div>
                    <div class="text-sm text-gray-500 mt-1">
                      Due: {{ formatDate(assignment.due_date) }}
                    </div>
                  </div>
                </div>
                <button
                  @click="goToContent(assignment.id)"
                  class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition flex items-center gap-2 flex-shrink-0"
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
        if (token) {
          axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        }
        
        const response = await axios.get(`/api/student/${this.course_id}/course-content`);
        const data = response.data;

        this.course.title = data.course_title;
        this.materials = data.materials.map((m, index) => ({
          id: m.id || index, // Ensure each material has an ID
          title: m.title,
          pdf_path: m.pdf_path,
        }));
        this.assignments = data.assignments.map((a) => ({
          id: a.id,
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
            this.error = error.response.data?.error || 'Failed to connect to the server. Please try again.';
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
    
    goToContent(assignmentId) {
       
        window.location.href = `/student/assignment/${assignmentId}`;
      
    },
    
    goToContentMaterial(materialId) {
      if (materialId) {
        window.location.href = `/student/materials/${materialId}`;
      }
    },
    
    viewMaterial(materialId) {
      if (materialId) {
        this.$router.push({
          name: 'MaterialView',
          params: {
            course_id: this.course_id,
            material_id: materialId,
          },
        });
      }
    },
    
    viewAssignment(assignmentId) {
      if (assignmentId) {
        this.$router.push({
          name: 'AssignmentView',
          params: {
            course_id: this.course_id,
            assignment_id: assignmentId,
          },
        });
      }
    },
  },
};
</script>