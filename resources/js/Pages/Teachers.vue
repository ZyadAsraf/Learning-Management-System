<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold mb-4">Teachers Page</h1>
        <button
          @click="openModal('create', {})"
          class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow transition flex items-center gap-2"
        >
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
          Create Teacher
        </button>
      </div>
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
      <div v-if="loading" class="text-gray-500">Loading teachers...</div>
      <div v-else>
        <div v-if="teachers.length === 0" class="text-gray-500">No teachers found.</div>
        <div v-if="viewType === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div v-for="teacher in teachers" :key="teacher.id" class="bg-white rounded-2xl shadow p-6 flex flex-col items-center w-full max-w-xs mx-auto">
            <img :src="teacher.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(teacher.name) + '&background=random'" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow -mt-12 mb-2" alt="Avatar" />
            <div class="text-lg font-semibold mt-2">{{ teacher.name }}</div>
            <div class="mt-1 mb-4">
              <span class="bg-blue-100 text-blue-700 px-4 py-1 rounded-full text-sm font-medium">Teacher</span>
            </div>
            <div class="flex gap-4 mt-2">
              <button @click="openModal('edit', teacher)" class="w-10 h-10 flex items-center justify-center rounded-full bg-yellow-100 hover:bg-yellow-200 text-yellow-600 shadow transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.1 2.1 0 1 1 2.97 2.97L7.5 19.788l-4 1 1-4 13.362-13.3z" />
                </svg>
              </button>
              <button @click="openModal('delete', teacher)" class="w-10 h-10 flex items-center justify-center rounded-full bg-red-100 hover:bg-red-200 text-red-600 shadow transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
              <button @click="openModal('details', teacher)" class="w-10 h-10 flex items-center justify-center rounded-full bg-green-100 hover:bg-green-200 text-green-600 shadow transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
              </button>
            </div>
          </div>
        </div>
        <div v-else>
          <div v-for="teacher in teachers" :key="teacher.id" class="bg-white rounded-2xl shadow p-6 flex items-center w-full max-w-2xl mx-auto mb-4">
            <img :src="teacher.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(teacher.name) + '&background=random'" class="w-16 h-16 rounded-full object-cover border-4 border-white shadow mr-6" alt="Avatar" />
            <div class="flex-1">
              <div class="text-lg font-semibold">{{ teacher.name }}</div>
              <div class="text-gray-500">{{ teacher.email }}</div>
            </div>
            <div class="flex gap-2">
              <button @click="openModal('edit', teacher)" class="w-10 h-10 flex items-center justify-center rounded-full bg-yellow-100 hover:bg-yellow-200 text-yellow-600 shadow transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.1 2.1 0 1 1 2.97 2.97L7.5 19.788l-4 1 1-4 13.362-13.3z" />
                </svg>
              </button>
              <button @click="openModal('delete', teacher)" class="w-10 h-10 flex items-center justify-center rounded-full bg-red-100 hover:bg-red-200 text-red-600 shadow transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
              <button @click="openModal('details', teacher)" class="w-10 h-10 flex items-center justify-center rounded-full bg-green-100 hover:bg-green-200 text-green-600 shadow transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
      <ActionModal
        :show="showModal"
        :mode="modalMode"
        :item="selectedTeacher"
        @close="closeModal"
        @confirm="handleModalAction"
      />
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import ActionModal from '@/components/ActionModal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { onMounted } from 'vue';
import axios from 'axios';

const teachers = ref([]);
const loading = ref(true);
const viewType = ref('grid');
const showModal = ref(false);
const modalMode = ref('edit');
const selectedTeacher = ref({});

const loadTeachers = async () => {
  loading.value = true;
  try {
    const token = localStorage.getItem('token');
    if (token) axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    const response = await axios.get('/api/teachers');
    teachers.value = response.data.map(teacher => ({ ...teacher, type: 'Teacher' }));
  } catch (error) {
    console.error('Failed to load teachers', error);
  } finally {
    loading.value = false;
  }
};

onMounted(loadTeachers);

const openModal = (mode, teacher) => {
  modalMode.value = mode;
  selectedTeacher.value = { ...teacher };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const handleModalAction = async (action) => {
  const token = localStorage.getItem('token');
  if (token) axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

  if (action.action === 'create') {
    try {
      await axios.post('/api/teachers', action.data);
      loadTeachers();
    } catch (error) {
      console.error('Failed to create teacher', error);
      alert('Failed to create teacher.');
    }
  } else if (action.action === 'edit') {
    try {
      await axios.put(`/api/teachers/${action.data.id}`, action.data);
      loadTeachers();
    } catch (error) {
      console.error('Failed to update teacher', error);
      alert('Failed to update teacher.');
    }
  } else if (action.action === 'delete') {
    try {
      await axios.delete(`/api/teachers/${action.id}`);
      loadTeachers();
    } catch (error) {
      console.error('Failed to delete teacher', error);
      alert('Failed to delete teacher.');
    }
  }
  closeModal();
};
</script>