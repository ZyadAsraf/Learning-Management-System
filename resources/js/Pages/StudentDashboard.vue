<template>
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gray-50">
      <!-- Header -->
      <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center py-6">
            <h1 class="text-3xl font-bold text-gray-900">My Courses</h1>
            <div class="text-sm text-gray-600">
              Welcome back, {{ studentName }}
            </div>
          </div>
        </div>
      </div>
      
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Loading State -->
        <div v-if="loading" class="text-gray-500 text-center py-8">
          <div class="animate-pulse">Loading your courses...</div>
        </div>
        
        <!-- Error State -->
        <div v-else-if="error" class="text-center py-12">
          <div class="text-red-500 text-lg mb-2">Failed to load courses</div>
          <p class="text-gray-500 mb-4">{{ error }}</p>
          <button 
            @click="loadCourses"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
          >
            Retry
          </button>
        </div>
        
        <div v-else>
          <!-- Empty State -->
          <div v-if="courses.length === 0" class="text-center py-12">
            <div class="text-gray-400 text-lg">No courses enrolled yet.</div>
            <p class="text-gray-500 mt-2">Contact your administrator to get enrolled in courses.</p>
          </div>
          
          <div v-else>
            <!-- Course Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
              <!-- Total Courses -->
              <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm font-medium text-gray-600">Total Courses</p>
                    <p class="text-3xl font-bold text-gray-900">{{ courses.length }}</p>
                  </div>
                  <div class="p-3 rounded-full bg-blue-100">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                  </div>
                </div>
              </div>
              
              <!-- In Progress Courses -->
              <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm font-medium text-gray-600">In Progress</p>
                    <p class="text-3xl font-bold text-gray-900">{{ inProgressCourses }}</p>
                  </div>
                  <div class="p-3 rounded-full bg-orange-100">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                </div>
              </div>
            </div>

            <!-- Course Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div 
                v-for="course in courses" 
                :key="course.id" 
                class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 min-h-[400px] flex flex-col"
              >
                <!-- Course Header with Gradient -->
                <div class="h-32 bg-gradient-to-br relative overflow-hidden" :class="getGradientClass(course.id)">
                  <div class="absolute inset-0 bg-blue-500"></div>
                  <div class="absolute top-4 right-4">
                    <button 
                      @click="toggleCourseMenu(course.id)"
                      class="p-2 rounded-full bg-white bg-opacity-20 hover:bg-opacity-30 transition-all"
                    >
                      <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                      </svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div 
                      v-if="activeCourseMenu === course.id"
                      class="absolute right-0 top-12 bg-white rounded-lg shadow-lg py-2 min-w-48 z-10"
                    >
                      <button 
                        @click="viewCourseDetails(course)"
                        class="w-full text-left px-4 py-1 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        View Details
                      </button>
                      <button 
                        @click="downloadMaterials(course)"
                        class="w-full text-left px-4 py-1 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download Materials
                      </button>
                    </div>
                  </div>
                  
                  <!-- Decorative Pattern -->
                  <div class="absolute bottom-0 right-0 w-24 h-24 opacity-20">
                    <div class="w-full h-full rounded-full border-4 border-white transform translate-x-8 translate-y-8"></div>
                  </div>
                </div>
                
                <!-- Course Content -->
                <div class="p-6 flex-1 flex flex-col justify-between">
                  <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                      {{ course.course_title || course.name }}
                    </h3>
                    <p v-if="course.department" class="text-sm text-gray-600 mb-1">
                      {{ course.department }}
                    </p>
                    <p class="text-xs text-gray-500 mb-3">
                      Instructor: {{ course.teacher || course.instructor || 'Not Assigned' }}
                    </p>
                    
                    <!-- Progress Section -->
                    <div class="mb-4">
                      <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Progress</span>
                        <span class="text-sm font-bold" :class="getProgressColor(getProgressPercentage(course))">
                          {{ getProgressPercentage(course) }}% complete
                        </span>
                      </div>
                      <div class="w-full bg-gray-200 rounded-full h-2">
                        <div 
                          class="h-2 rounded-full transition-all duration-500" 
                          :class="getProgressBarColor(getProgressPercentage(course))"
                          :style="{ width: getProgressPercentage(course) + '%' }"
                        ></div>
                      </div>
                    </div>
                    
                    <!-- Assignments Summary -->
                    <div v-if="course.assignments" class="mb-4">
                      <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-600">Assignments Completed:</span>
                        <span class="font-medium">
                          {{ course.assignments.submit || 0 }} of {{ course.assignments.count || 0 }}
                        </span>
                      </div>
                    </div>
                    
                    <!-- Course Meta Info -->
                    <div v-if="course.enrollment_date" class="text-xs text-gray-500 mb-4">
                      <div>Enrolled: {{ formatDate(course.enrollment_date) }}</div>
                      <div v-if="course.completion_date">
                        Completed: {{ formatDate(course.completion_date) }}
                      </div>
                    </div>
                  </div>
                  
                  <!-- Status Badge and Action Button -->
                  <div class="flex items-center justify-between mt-auto">
                    <span 
                      class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                      :class="getStatusBadge(getProgressPercentage(course))"
                    >
                      {{ getStatusText(getProgressPercentage(course)) }}
                    </span>
                    
                    <!-- Action Button -->
<!-- Replace the existing button in your course card with this -->
<button 
  @click="goToCourseContent(course.id)"
  class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center gap-2"
>
  <svg v-if="getProgressPercentage(course) === 100" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
  </svg>
  <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M19 10a9 9 0 11-18 0 9 9 0 0118 0z"></path>
  </svg>
  {{ getProgressPercentage(course) === 100 ? 'Review' : 'Continue' }}
</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Course Details Modal -->
      <div v-if="showDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-900">Course Details</h2>
            <button @click="closeDetailsModal" class="text-gray-500 hover:text-gray-700">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          
          <div v-if="selectedCourse" class="space-y-4">
            <div>
              <h3 class="font-semibold text-gray-900">{{ selectedCourse.course_title || selectedCourse.name }}</h3>
              <p v-if="selectedCourse.department" class="text-gray-600">{{ selectedCourse.department }}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <span class="text-sm font-medium text-gray-600">Instructor:</span>
                <p class="text-gray-900">{{ selectedCourse.teacher || selectedCourse.instructor || 'Not Assigned' }}</p>
              </div>
              <div>
                <span class="text-sm font-medium text-gray-600">Progress:</span>
                <p class="text-gray-900">{{ getProgressPercentage(selectedCourse) }}%</p>
              </div>
              
              <div v-if="selectedCourse.assignments">
                <span class="text-sm font-medium text-gray-600">Assignments Completed:</span>
                <p class="text-gray-900">{{ selectedCourse.assignments.submit || 0 }} of {{ selectedCourse.assignments.count || 0 }}</p>
              </div>
              
              <div v-if="selectedCourse.enrollment_date">
                <span class="text-sm font-medium text-gray-600">Enrollment Date:</span>
                <p class="text-gray-900">{{ formatDate(selectedCourse.enrollment_date) }}</p>
              </div>
              
              <div v-if="selectedCourse.completion_date">
                <span class="text-sm font-medium text-gray-600">Completion Date:</span>
                <p class="text-gray-900">{{ formatDate(selectedCourse.completion_date) }}</p>
              </div>
            </div>
            
            <div v-if="selectedCourse.description">
              <span class="text-sm font-medium text-gray-600">Description:</span>
              <p class="text-gray-900 mt-1 whitespace-pre-line">{{ selectedCourse.description }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';
import { Inertia } from '@inertiajs/inertia';

const courses = ref([]);
const loading = ref(true);
const error = ref(null);
const studentName = ref('Student');
const activeCourseMenu = ref(null);
const showDetailsModal = ref(false);
const selectedCourse = ref(null);

const inProgressCourses = computed(() => 
  courses.value.filter(course => getProgressPercentage(course) > 0 && getProgressPercentage(course) < 100).length
);

// Load student courses from API
const loadCourses = async () => {
  loading.value = true;
  error.value = null;
  try {
    const response = await axios.get('/api/student/courses', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    });
    
    courses.value = response.data.courses || [];
    studentName.value = response.data.student_name || 'Student';
    
  } catch (err) {
    console.error('Failed to load courses:', err);
    error.value = err.response?.data?.message || err.message || 'Failed to load courses';
    courses.value = [];
  } finally {
    loading.value = false;
  }
};

// Utility functions
const getGradientClass = (courseId) => {
  const gradients = [
    'from-pink-400 to-pink-600',
    'from-purple-400 to-pink-500',
    'from-blue-400 to-blue-600',
    'from-yellow-400 to-orange-500',
    'from-indigo-400 to-purple-500',
    'from-green-400 to-blue-500',
    'from-red-400 to-pink-500'
  ];
  return gradients[courseId % gradients.length];
};

const getProgressPercentage = (course) => {
  if (course.progress !== undefined) return course.progress;
  if (course.assignments?.count > 0) {
    return Math.round((course.assignments.submit / course.assignments.count) * 100);
  }
  return 0;
};

const getProgressColor = (progress) => {
  if (progress === 100) return 'text-green-600';
  if (progress >= 50) return 'text-blue-600';
  if (progress >= 25) return 'text-orange-600';
  return 'text-gray-600';
};

const getProgressBarColor = (progress) => {
  if (progress === 100) return 'bg-green-500';
  if (progress >= 50) return 'bg-blue-500';
  if (progress >= 25) return 'bg-orange-500';
  return 'bg-gray-400';
};

const getStatusBadge = (progress) => {
  if (progress === 100) return 'bg-green-100 text-green-800';
  if (progress > 0) return 'bg-blue-100 text-blue-800';
  return 'bg-gray-100 text-gray-800';
};

const getStatusText = (progress) => {
  if (progress === 100) return 'Completed';
  if (progress > 0) return 'In Progress';
  return 'Not Started';
};

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString('en-US', options);
};

const toggleCourseMenu = (courseId) => {
  activeCourseMenu.value = activeCourseMenu.value === courseId ? null : courseId;
};

const viewCourseDetails = (course) => {
  selectedCourse.value = course;
  showDetailsModal.value = true;
  activeCourseMenu.value = null;
};

const closeDetailsModal = () => {
  showDetailsModal.value = false;
  selectedCourse.value = null;
};

const downloadMaterials = async (course) => {
  try {
    alert(`Preparing to download materials for "${course.course_title || course.name}"`);
    activeCourseMenu.value = null;
  } catch (err) {
    console.error('Download failed:', err);
    alert('Failed to download materials. Please try again.');
  }
};

const handleCourseAction = (course, action) => {
  switch (action) {
    case 'continue':
      alert(`Continuing course: "${course.course_title || course.name}"`);
      break;
    case 'review':
      alert(`Reviewing completed course: "${course.course_title || course.name}"`);
      break;
  }
};

// Close dropdowns when clicking outside
const handleClickOutside = (event) => {
  if (!event.target.closest('.relative')) {
    activeCourseMenu.value = null;
  }
};
const goToCourseContent = (courseId) => {
  window.location.href = `/student/course/${courseId}`;
};
onMounted(() => {
  loadCourses();
  document.addEventListener('click', handleClickOutside);
});
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: .5;
  }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>