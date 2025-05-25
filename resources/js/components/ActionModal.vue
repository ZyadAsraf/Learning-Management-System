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

            <!-- Select field with options array -->
            <select v-if="field.type === 'select' && Array.isArray(field.options)"
                    v-model="formData[key]"
                    class="border rounded-lg px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-purple-300">
              <option value="">Select {{ field.label }}</option>
              <option v-for="option in field.options" :key="option.value" :value="option.value">
                {{ option.label }}
              </option>
            </select>

            <!-- Select field with simple string options -->
            <select v-else-if="field.type === 'select'"
                    v-model="formData[key]"
                    class="border rounded-lg px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-purple-300">
              <option value="">Select {{ field.label }}</option>
              <option v-for="option in field.options" :key="option" :value="option">{{ option }}</option>
            </select>

            <!-- Default input field -->
            <input v-else
                   v-model="formData[key]"
                   :type="field.type || 'text'"
                   class="border rounded-lg px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-purple-300" />
          </div>
        </div>
        
        <div v-else-if="mode === 'delete'" class="py-2">
          <p class="text-center text-gray-700">Are you sure you want to delete <span class="font-semibold">{{ itemName }}</span>?</p>
        </div>
        
        <div v-else-if="mode === 'details'" class="flex flex-col items-center py-4">
          <div v-if="itemAvatar" class="mb-4 flex items-center">
            <img v-if="itemAvatar.startsWith('http')" :src="itemAvatar" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow" alt="Avatar" @error="handleImageError" />
            <div v-else class="w-24 h-24 rounded-full bg-purple-800 text-white flex items-center justify-center text-3xl border-4 border-white shadow">{{ itemAvatar }}</div>
          </div>
          <h3 class="text-xl font-semibold mb-1 text-gray-800">{{ itemName }}</h3>
          <div v-if="itemEmail" class="flex items-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-gray-500">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zm-4.5 3.75a6.75 6.75 0 01-6.75-6.75v-.75h13.5v.75a6.75 6.75 0 01-6.75 6.75z" />
            </svg>
            <p class="text-gray-500">{{ itemEmail }}</p>
          </div>
          <div v-if="additionalDetails" class="w-full mt-2">
            <div v-for="(value, key) in additionalDetails" :key="key" class="flex justify-between py-2 border-t border-gray-100">
              <span class="text-gray-600">{{ key }}</span>
              <span class="font-medium text-gray-800">{{ value }}</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="bg-gray-50 px-6 py-4 rounded-b-xl flex" :class="[mode === 'details' ? 'justify-center' : 'justify-end space-x-3']">
        <template v-if="mode === 'create'">
          <button @click="confirmAction" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">
            Create
          </button>
          <button @click="closeModal" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg shadow transition">
            Cancel
          </button>
        </template>
        <template v-else-if="mode === 'edit'">
          <button @click="confirmAction" class="px-4 py-2 bg-purple-700 hover:bg-purple-800 text-white rounded-lg shadow transition">
            Save
          </button>
          <button @click="closeModal" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg shadow transition">
            Cancel
          </button>
        </template>
        <template v-else-if="mode === 'delete'">
          <button @click="confirmAction" class="w-12 h-12 flex items-center justify-center rounded-full bg-red-500 hover:bg-red-600 text-white shadow transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
            </svg>
          </button>
          <button @click="closeModal" class="w-12 h-12 flex items-center justify-center rounded-full bg-gray-300 hover:bg-gray-400 text-gray-800 shadow transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </template>
        <template v-else-if="mode === 'details'">
          <button @click="closeModal" class="px-4 py-2 bg-purple-700 hover:bg-purple-800 text-white rounded-lg shadow transition">
            Close
          </button>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
  show: { type: Boolean, default: false },
  mode: { type: String, default: 'edit', validator: (value) => ['create', 'edit', 'delete', 'details'].includes(value) },
  item: { type: Object, default: () => ({}) },
  formFields: {
    type: Object,
    default: () => ({
      name: { label: 'Name', type: 'text' },
      email: { label: 'Email', type: 'email' },
      password: { label: 'Password', type: 'password' },
    })
  }
});

const emit = defineEmits(['close', 'confirm']);

const title = computed(() => {
  const type = props.item.type || 'Item';
  switch (props.mode) {
    case 'create': return `Create New ${type}`;
    case 'edit': return `Edit ${type}`;
    case 'delete': return `Delete ${type}`;
    case 'details': return `${type} Information`;
    default: return 'Modal';
  }
});

const itemName = computed(() => {
  if (props.item.course && props.item.student) {
    return `${props.item.student.name} - ${props.item.course.title}`;
  }
  return props.item.name || '';
});

const itemEmail = computed(() => props.item.email || (props.item.student && props.item.student.email) || '');

const itemAvatar = computed(() => {
  const name = props.item.name || (props.item.student && props.item.student.name) || '';
  if (props.item.avatar && props.item.avatar.startsWith('http')) {
    return props.item.avatar;
  }
  if (name) {
    const initials = name.split(' ').map(n => n[0]).join('').toUpperCase();
    return initials.slice(0, 2);
  }
  return '';
});

const additionalDetails = computed(() => {
  if (props.item.course && props.item.student) {
    return {
      'Course': props.item.course.title,
      'Category': props.item.course.category,
      'Duration': props.item.course.duration,
      'Student': props.item.student.name,
      'Student Email': props.item.student.email
    };
  }
  return null;
});

const formData = ref({});

watch(() => props.item, (newItem) => {
  if (props.mode === 'create') {
    formData.value = {};
    Object.keys(props.formFields).forEach(key => {
      formData.value[key] = '';
    });
  } else {
    formData.value = { ...newItem };
  }
}, { immediate: true, deep: true });

const closeModal = () => emit('close');

const confirmAction = () => {
  if (props.mode === 'create') emit('confirm', { action: 'create', data: formData.value });
  else if (props.mode === 'edit') emit('confirm', { action: 'edit', data: formData.value });
  else if (props.mode === 'delete') emit('confirm', { action: 'delete', id: props.item.id });
  closeModal();
};

const handleImageError = (event) => {
  event.target.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(itemName.value) + '&background=random';
};
</script>