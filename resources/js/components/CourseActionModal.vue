<template>
  <div v-if="show" class="fixed inset-0 flex items-center justify-center z-50">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-50" @click="closeModal"></div>
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 z-10">
      <div class="flex items-center justify-between px-6 py-4 border-b bg-purple-700 text-white rounded-t-xl">
        <h2 class="text-2xl font-bold">{{ title }}</h2>
        <button @click="closeModal" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-purple-600 text-white transition">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="p-6">
        <div v-if="mode === 'create' || mode === 'edit'" class="space-y-4">
          <div v-for="(field, key) in formFields" :key="key" class="mb-4">
            <label class="block mb-1 text-gray-700">{{ field.label }}</label>

            <select v-if="field.type === 'select' && Array.isArray(field.options)"
                    v-model="formData[key]"
                    class="border rounded-lg px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-purple-300">
              <option value="">Select {{ field.label }}</option>
              <option v-for="option in field.options" :key="option.value" :value="option.value">
                {{ option.label }}
              </option>
            </select>

            <select v-else-if="field.type === 'select'"
                    v-model="formData[key]"
                    class="border rounded-lg px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-purple-300">
              <option value="">Select {{ field.label }}</option>
              <option v-for="option in field.options" :key="option" :value="option">{{ option }}</option>
            </select>

            <input v-else
                   v-model="formData[key]"
                   :type="field.type || 'text'"
                   class="border rounded-lg px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-purple-300" />
          </div>
        </div>

        <div v-else-if="mode === 'delete'" class="py-2">
          <p class="text-center text-gray-700">Are you sure you want to delete <span class="font-semibold">{{ courseTitle }}</span>?</p>
        </div>

        <div v-else-if="mode === 'details'" class="py-4 space-y-2">
          <h3 class="text-xl font-semibold text-center text-gray-800">{{ courseTitle }}</h3>
          <p v-if="courseDescription" class="text-gray-600 text-center">{{ courseDescription }}</p>
          <div v-if="additionalDetails" class="mt-4">
            <div v-for="(value, key) in additionalDetails" :key="key" class="flex justify-between py-2 border-t border-gray-100">
              <span class="text-gray-600">{{ key }}</span>
              <span class="font-medium text-gray-800">{{ value }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-gray-50 px-6 py-4 rounded-b-xl flex" :class="[mode === 'details' ? 'justify-center' : 'justify-end space-x-3']">
        <template v-if="mode === 'create'">
          <button @click="confirmAction" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">Create</button>
          <button @click="closeModal" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg shadow transition">Cancel</button>
        </template>
        <template v-else-if="mode === 'edit'">
          <button @click="confirmAction" class="px-4 py-2 bg-purple-700 hover:bg-purple-800 text-white rounded-lg shadow transition">Save</button>
          <button @click="closeModal" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg shadow transition">Cancel</button>
        </template>
        <template v-else-if="mode === 'delete'">
          <button @click="confirmAction" class="w-12 h-12 flex items-center justify-center rounded-full bg-red-500 hover:bg-red-600 text-white shadow transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9..." />
            </svg>
          </button>
          <button @click="closeModal" class="w-12 h-12 flex items-center justify-center rounded-full bg-gray-300 hover:bg-gray-400 text-gray-800 shadow transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </template>
        <template v-else-if="mode === 'details'">
          <button @click="closeModal" class="px-4 py-2 bg-purple-700 hover:bg-purple-800 text-white rounded-lg shadow transition">Close</button>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
  show: Boolean,
  mode: { type: String, default: 'edit', validator: val => ['create', 'edit', 'delete', 'details'].includes(val) },
  item: Object,
  formFields: {
    type: Object,
    default: () => ({
      title: { label: 'Title', type: 'text' },
      description: { label: 'Description', type: 'text' },
      teacher_id: { label: 'Teacher', type: 'select', options: [] },
      is_public: { label: 'Public', type: 'select', options: ['Yes', 'No'] }
    })
  }
});

const emit = defineEmits(['close', 'confirm']);

const title = computed(() => {
  switch (props.mode) {
    case 'create': return 'Create New Course';
    case 'edit': return 'Edit Course';
    case 'delete': return 'Delete Course';
    case 'details': return 'Course Details';
    default: return 'Course Modal';
  }
});

const formData = ref({
  title: '',
  description: '',
  teacher_id: '',
  is_public: ''
});

const courseTitle = computed(() => props.item?.title || '');
const courseDescription = computed(() => props.item?.description || '');

const additionalDetails = computed(() => {
  if (!props.item) return null;
  const { teacher, ...rest } = props.item;
  const details = {};
  if (teacher?.name) details['Teacher'] = teacher.name;
  if ('is_public' in rest) details['Public'] = rest.is_public ? 'Yes' : 'No';
  return Object.keys(details).length ? details : null;
});

watch(
  () => [props.mode, props.item],
  () => {
    if (['edit'].includes(props.mode) && props.item) {
      for (const key in formData.value) {
        formData.value[key] = props.item[key] ?? '';
      }
    } else if (props.mode === 'create') {
      for (const key in formData.value) formData.value[key] = '';
    }
  },
  { immediate: true }
);

function closeModal() {
  emit('close');
}

function confirmAction() {
  emit('confirm', { mode: props.mode, data: formData.value });
}
</script>

<style scoped>
.form-checkbox {
  appearance: none;
  -webkit-appearance: none;
  background-color: white;
  border: 1.5px solid #a78bfa; 
  width: 20px;
  height: 20px;
  border-radius: 0.25rem;
  cursor: pointer;
  position: relative;
}
.form-checkbox:checked {
  background-color: #7c3aed; 
  border-color: #7c3aed;
}
.form-checkbox:checked::after {
  content: '✔';
  position: absolute;
  top: 2px;
  left: 5px;
  color: white;
  font-size: 14px;
  font-weight: bold;
}
</style>
