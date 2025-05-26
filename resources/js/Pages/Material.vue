<template>
  <div class="material-page p-8 bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto">
      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Material Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-6">
          <h1 class="text-3xl font-bold">{{ material.title || 'Loading...' }}</h1>
          <p class="text-sm mt-2">Course: {{ course.title }}</p>
        </div>

        <!-- Loading or Error State -->
        <div v-if="loading || error" class="p-6">
          <div
            class="bg-white rounded-lg shadow-md p-6 text-center"
            :class="{ 'border-t-4 border-blue-600': loading, 'border-t-4 border-red-500': error }"
          >
            <div v-if="loading" class="text-gray-900">
              <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
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

        <!-- Material Content -->
        <div v-if="!loading && !error" class="p-6">
          <!-- Material Description -->
          <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Material Details</h2>
            <p class="text-gray-700 mb-6">{{ material.description || 'No description provided.' }}</p>
          </section>

          <!-- PDF Files Section -->
          <section>
            <h3 class="text-xl font-bold text-gray-900 mb-4">Course Materials</h3>
            
            <!-- Single PDF File Display -->
            <div v-if="material.pdf_path" class="grid gap-4">
              <div 
                @click="openPDF(material.pdf_path)"
                class="pdf-container bg-gradient-to-br from-red-50 to-red-100 border-2 border-red-200 rounded-lg p-6 cursor-pointer hover:shadow-lg hover:border-red-300 transition-all duration-300 transform hover:scale-105"
              >
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-4">
                    <!-- PDF Icon -->
                    <div class="bg-red-500 text-white p-3 rounded-lg">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-8 h-8"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"
                        />
                      </svg>
                    </div>
                    
                    <!-- File Info -->
                    <div>
                      <h4 class="font-semibold text-gray-900 text-lg">{{ getFileName(material.pdf_path) }}</h4>
                      <p class="text-gray-600 text-sm">PDF Document</p>
                    </div>
                  </div>
                  
                  <!-- Open Icon -->
                  <div class="text-red-500">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="w-6 h-6"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"
                      />
                    </svg>
                  </div>
                </div>
                
                <!-- Click instruction -->
                <div class="mt-4 text-center">
                  <span class="text-sm text-gray-500 bg-white px-3 py-1 rounded-full">Click to open in new tab</span>
                </div>
              </div>
            </div>

            <!-- No PDF Available -->
            <div v-else class="text-center py-8">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-16 h-16 mx-auto mb-4 text-gray-400"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 18.75h-1.5A1.125 1.125 0 0112 18v-2.25m0 0h4.5V7.875a1.125 1.125 0 011.125-1.125H20.25M2.25 13.5h3.86a2.25 2.25 0 002.012-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.218a2.25 2.25 0 012.013 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.859m-19.5.338V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 00-2.15-1.588H6.911a2.25 2.25 0 00-2.15 1.588L2.35 13.177a2.25 2.25 0 00-.1.661z"
                />
              </svg>
              <p class="text-gray-500 text-lg">No PDF material available for this course</p>
            </div>
          </section>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'MaterialView',
  props: {
    course_id: {
      type: [String, Number],
      required: true,
    },
    material_id: {
      type: [String, Number],
      required: true,
    },
  },
  data() {
    return {
      course: {
        title: 'Advanced Web Development',
      },
      material: {
        title: 'JavaScript Fundamentals and ES6 Features',
        description: 'This comprehensive material covers essential JavaScript concepts including variables, functions, objects, arrays, and modern ES6+ features like arrow functions, destructuring, promises, and async/await. Perfect for both beginners and intermediate developers looking to strengthen their JavaScript foundation.',
        pdf_path: '/materials/javascript-fundamentals-guide.pdf',
      },
      loading: false,
      error: null,
    };
  },
  mounted() {
    // Uncomment the line below to fetch real data from API
    // this.fetchMaterialData();
    
    // For demo purposes, we're using the sample data defined above
    console.log('Component loaded with sample data');
  },
  methods: {
    async fetchMaterialData() {
      this.loading = true;
      this.error = null;
      try {
        const token = localStorage.getItem('token');
        if (token) axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        
        const response = await axios.get(`/api/student/course/${this.course_id}/material/${this.material_id}`);
        const data = response.data;

        this.course.title = data.course.title;
        this.material = {
          title: data.material.title,
          description: data.material.description,
          pdf_path: data.material.pdf_path,
        };
      } catch (error) {
        console.error('Error loading material data:', error);
        if (error.response) {
          if (error.response.status === 403) {
            this.error = 'You are not authorized to view this material.';
          } else if (error.response.status === 404) {
            this.error = 'Material not found.';
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
    
    openPDF(path) {
      if (!path) {
        alert('Invalid PDF path.');
        return;
      }
      // Open PDF in new tab
      window.open(path, '_blank');
    },
    
    getFileName(path) {
      if (!path) return 'Unknown File';
      
      // Extract filename from path
      const fileName = path.split('/').pop() || path;
      
      // Remove file extension for display (optional)
      return fileName.replace('.pdf', '') || 'Course Material';
    },
  },
};
</script>

<style scoped>
.pdf-container {
  transition: all 0.3s ease;
}

.pdf-container:hover {
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>