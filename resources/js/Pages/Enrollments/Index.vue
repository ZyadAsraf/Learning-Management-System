<template>
  <AuthenticatedLayout>
    <template #header>
      <h1 class="text-3xl font-bold mb-4">Enrollments Page</h1>
    </template>
    <div class="flex justify-end mb-4">
      <button
        @click="viewType = viewType === 'grid' ? 'list' : 'grid'"
        class="px-4 py-5 rounded bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium transition"
      >
        Switch to {{ viewType === 'grid' ? 'List' : 'Grid' }} View
      </button>
    </div>
    <div class="p-8">
      <div v-if="loading" class="text-gray-500">Loading enrollments...</div>
      <div v-else>
        <div v-if="enrollments.length === 0" class="text-gray-500">No enrollments found.</div>
        <div v-if="viewType === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div v-for="enrollment in enrollments" :key="enrollment.id" class="bg-white rounded-2xl shadow p-6 flex flex-col items-center w-full max-w-xs mx-auto">
            <img :src="enrollment.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(enrollment.student) + '&background=random'" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow -mt-12 mb-2" alt="Avatar" />
            <div class="text-lg font-semibold mt-2">{{ enrollment.student }}</div>
            <div class="mt-1 mb-4">
              <span class="bg-green-100 text-green-700 px-4 py-1 rounded-full text-sm font-medium">{{ enrollment.course }}</span>
            </div>
            <div class="flex gap-4 mt-2">
              <button
                @click="editEnrollment(enrollment)"
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-yellow-100 hover:bg-yellow-200 text-yellow-600 shadow transition"
              >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.1 2.1 0 1 1 2.97 2.97L7.5 19.788l-4 1 1-4 13.362-13.3z" />
                </svg>
                <span class="font-medium"></span>
              </button>
              <button @click="deleteEnrollment(enrollment.id)" class="w-10 h-10 flex items-center justify-center rounded-full bg-red-100 hover:bg-red-200 text-red-600 shadow transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
              <button class="w-10 h-10 flex items-center justify-center rounded-full bg-green-100 hover:bg-green-200 text-green-600 shadow transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
                </svg>
              </button>
            </div>
          </div>
        </div>
        <div v-else>
          <div v-for="enrollment in enrollments" :key="enrollment.id" class="bg-white rounded-2xl shadow p-6 flex items-center w-full max-w-2xl mx-auto mb-4">
            <img :src="enrollment.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(enrollment.student) + '&background=random'" class="w-16 h-16 rounded-full object-cover border-4 border-white shadow mr-6" alt="Avatar" />
            <div class="flex-1">
              <div class="text-lg font-semibold">{{ enrollment.student }}</div>
              <div class="text-gray-500">{{ enrollment.course }}</div>
            </div>
            <div class="flex gap-2">
              <button
                @click="editEnrollment(enrollment)"
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-yellow-100 hover:bg-yellow-200 text-yellow-600 shadow transition"
              >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.1 2.1 0 1 1 2.97 2.97L7.5 19.788l-4 1 1-4 13.362-13.3z" />
                </svg>
                <span class="font-medium"></span>
              </button>
              <button @click="deleteEnrollment(enrollment.id)" class="w-10 h-10 flex items-center justify-center rounded-full bg-red-100 hover:bg-red-200 text-red-600 shadow transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
              <button class="w-10 h-10 flex items-center justify-center rounded-full bg-green-100 hover:bg-green-200 text-green-600 shadow transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
      <!-- Edit Modal -->
      <Modal v-if="showEditModal" @close="showEditModal = false">
        <template #header>Enrollment</template>
        <template #body>
          <div class="mb-4">
            <label class="block mb-1">Student</label>
            <input v-model="editForm.student" class="border rounded px-3 py-2 w-full" />
          </div>
          <div class="mb-4">
            <label class="block mb-1">Course</label>
            <input v-model="editForm.course" class="border rounded px-3 py-2 w-full" />
          </div>
        </template>
        <template #footer>
          <button @click="updateEnrollment" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Save</button>
          <button @click="showEditModal = false" class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">Cancel</button>
        </template>
      </Modal>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import Modal from '@/components/Modal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const enrollments = ref([
  { id: 1, student: 'Alice Johnson', course: 'Math 101', avatar: '', },
  { id: 2, student: 'Bob Smith', course: 'Physics 201', avatar: '', },
  { id: 3, student: 'Charlie Brown', course: 'Chemistry 301', avatar: '', },
  { id: 4, student: 'Diana Prince', course: 'Biology 101', avatar: '', },
  { id: 5, student: 'Ethan Hunt', course: 'History 201', avatar: '', },
  { id: 6, student: 'Fiona Gallagher', course: 'Math 101', avatar: '', },
  { id: 7, student: 'George Miller', course: 'Physics 201', avatar: '', },
  { id: 8, student: 'Hannah Lee', course: 'Chemistry 301', avatar: '', },
]);
const loading = ref(false);
const showEditModal = ref(false);
const editForm = ref({ id: null, student: '', course: '' });
const viewType = ref('grid'); // 'grid' or 'list'

const editEnrollment = (enrollment) => {
  editForm.value = { ...enrollment };
  showEditModal.value = true;
};

const updateEnrollment = async () => {
  try {
    // Placeholder for actual API call
    showEditModal.value = false;
  } catch (e) {
    alert('Failed to update enrollment.');
  }
};

const deleteEnrollment = async (id) => {
  if (!confirm('Are you sure you want to delete this enrollment?')) return;
  try {
    // Placeholder for actual API call
  } catch (e) {
    alert('Failed to delete enrollment.');
  }
};
</script> 