<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold">Course Enrollments</h1>
        <button
          @click="openModal('create', {})"
          class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-lg font-medium transition flex items-center gap-2"
        >
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
          Create New Enrollment
        </button>
      </div>
    </template>
    
    <div class="p-8">
      <div v-if="loading" class="text-gray-500">Loading course enrollments...</div>
      <div v-else>
        <div v-if="courseEnrollments.length === 0" class="text-gray-500">No course enrollments found.</div>
        
        <!-- Course Tables -->
        <div v-else class="space-y-8">
          <div v-for="courseData in courseEnrollments" :key="courseData.course.id" class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Course Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-6">
              <h2 class="text-2xl font-bold mb-2">{{ courseData.course.title }}</h2>
              <div class="flex flex-wrap gap-4 text-blue-100">
                <span class="flex items-center gap-1">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                  </svg>
                  Category: {{ courseData.course.category }}
                </span>
                <span class="flex items-center gap-1">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Duration: {{ courseData.course.duration }}
                </span>
                <span class="flex items-center gap-1">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                  </svg>
                  Teacher: {{ courseData.course.teacher || 'Not Assigned' }}
                </span>
                <span class="flex items-center gap-1">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                  </svg>
                  {{ courseData.enrollments.length }} Students Enrolled
                </span>
              </div>
            </div>
            
            <!-- Students Table -->
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="enrollment in courseData.enrollments" :key="enrollment.id" class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                          <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-semibold">
                            {{ enrollment.student.name.charAt(0).toUpperCase() }}
                          </div>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900">{{ enrollment.student.name }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ enrollment.student.email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        Enrolled
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div class="flex gap-2">
                        <button
                          @click="openModal('edit', enrollment)"
                          class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-100 hover:bg-blue-200 text-blue-600 transition-colors"
                          title="Edit Enrollment"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.1 2.1 0 1 1 2.97 2.97L7.5 19.788l-4 1 1-4 13.362-13.3z" />
                          </svg>
                        </button>
                        <button 
                          @click="openModal('delete', enrollment)" 
                          class="w-8 h-8 flex items-center justify-center rounded-full bg-red-100 hover:bg-red-200 text-red-600 transition-colors"
                          title="Delete Enrollment"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                          </svg>
                        </button>
                        <button 
                          @click="openModal('details', enrollment)"
                          class="w-8 h-8 flex items-center justify-center rounded-full bg-green-100 hover:bg-green-200 text-green-600 transition-colors"
                          title="View Details"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          </svg>
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
              
              <!-- Empty state for course with no enrollments -->
              <div v-if="courseData.enrollments.length === 0" class="text-center py-8 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 mx-auto mb-3 text-gray-300">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                <p>No students enrolled in this course yet.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Modal Component -->
      <ActionModal
        :show="showModal"
        :mode="modalMode"
        :item="selectedEnrollment"
        :formFields="enrollmentFormFields"
        @close="closeModal"
        @confirm="handleModalAction"
      />
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import ActionModal from '@/components/ActionModal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';

const enrollments = ref([]);
const students = ref([]);
const courses = ref([]);
const loading = ref(true);
const showModal = ref(false);
const modalMode = ref('edit');
const selectedEnrollment = ref({});

// Group enrollments by course
const courseEnrollments = computed(() => {
  const grouped = {};
  
  // Initialize all courses
  courses.value.forEach(course => {
    grouped[course.id] = {
      course: course,
      enrollments: []
    };
  });
  
  // Add enrollments to their respective courses
  enrollments.value.forEach(enrollment => {
    if (grouped[enrollment.course.id]) {
      grouped[enrollment.course.id].enrollments.push(enrollment);
    }
  });
  
  return Object.values(grouped);
});

// Form fields configuration for the modal
const enrollmentFormFields = computed(() => ({
  student_id: {
    label: 'Student',
    type: 'select',
    options: students.value.map(student => ({
      value: student.id,
      label: `${student.name} (${student.email})`
    }))
  },
  course_id: {
    label: 'Course',
    type: 'select',
    options: courses.value.map(course => ({
      value: course.id,
      label: `${course.title} - ${course.category}`
    }))
  }
}));

// Load enrollments, students, and courses
const loadData = async () => {
  loading.value = true;
  try {
    const token = localStorage.getItem('token');
    if (token) axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    
    const response = await axios.get('/api/enrollments');
    enrollments.value = response.data.enrollments.map(enrollment => ({
      ...enrollment,
      type: 'Enrollment'
    }));
    students.value = response.data.students;
    courses.value = response.data.courses;
  } catch (error) {
    console.error('Failed to load data', error);
    alert('Failed to load enrollment data.');
  } finally {
    loading.value = false;
  }
};

onMounted(loadData);

const openModal = (mode, enrollment) => {
  modalMode.value = mode;
  if (mode === 'create') {
    selectedEnrollment.value = { type: 'Enrollment' };
  } else {
    selectedEnrollment.value = { ...enrollment };
  }
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const handleModalAction = async (action) => {
  try {
    if (action.action === 'create') {
      await axios.post('/api/enrollments', {
        student_id: action.data.student_id,
        course_id: action.data.course_id
      });
      alert('Enrollment created successfully!');
      loadData();
    } else if (action.action === 'edit') {
      await axios.put(`/api/enrollments/${action.data.id}`, {
        student_id: action.data.student_id,
        course_id: action.data.course_id
      });
      alert('Enrollment updated successfully!');
      loadData();
    } else if (action.action === 'delete') {
      await axios.delete(`/api/enrollments/${action.id}`);
      alert('Enrollment deleted successfully!');
      loadData();
    }
  } catch (error) {
    console.error('Action failed', error);
    if (error.response && error.response.data && error.response.data.message) {
      alert(error.response.data.message);
    } else {
      alert('Action failed. Please try again.');
    }
  }
  closeModal();
};
</script>