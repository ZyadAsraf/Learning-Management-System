<template>
  <div class="py-8">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
      <h2 class="text-2xl font-semibold mb-4">Roles</h2>

      <!-- Create Role Form -->
      <form @submit.prevent="submitForm" class="flex gap-2 mb-6">
        <input v-model="form.name" type="text" placeholder="New role name" class="border rounded px-2 py-1 flex-1" required />
        <button type="submit" :disabled="form.processing" class="bg-indigo-600 text-white px-4 py-1 rounded hover:bg-indigo-700">
          Add
        </button>
      </form>
      <div v-if="form.errors.name" class="text-red-600 text-sm mb-2">{{ form.errors.name }}</div>

      <!-- Roles Table -->
      <table class="w-full border">
        <thead>
          <tr class="bg-gray-100">
            <th class="p-2 text-left">ID</th>
            <th class="p-2 text-left">Name</th>
            <th class="p-2 text-left">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="role in roles.data" :key="role.id">
            <td class="p-2">{{ role.id }}</td>
            <td class="p-2">
              <span v-if="editingId !== role.id">{{ role.name }}</span>
              <input v-else v-model="editForm.name" type="text" class="border rounded px-2 py-1" />
            </td>
            <td class="p-2">
              <button v-if="editingId !== role.id" @click="startEdit(role)" class="text-indigo-600 hover:underline mr-2">Edit</button>
              <button v-else @click="updateRole(role)" :disabled="editForm.processing" class="text-green-600 hover:underline mr-2">Save</button>
              <button v-if="editingId === role.id" @click="cancelEdit" class="text-gray-500 hover:underline mr-2">Cancel</button>
              <button @click="deleteRole(role)" class="text-red-600 hover:underline">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div v-if="roles.links && roles.links.length > 3" class="mt-4 flex gap-2">
        <button v-for="link in roles.links" :key="link.label" :disabled="!link.url || link.active"
                @click="goTo(link.url)" v-html="link.label"
                class="px-2 py-1 rounded border"
                :class="{'bg-indigo-600 text-white': link.active, 'bg-white text-gray-700': !link.active}"></button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';

const props = defineProps({
  roles: {
    type: Object,
    required: true
  }
});

const form = useForm({ name: '' });
const editingId = ref(null);
const editForm = useForm({ name: '' });

function submitForm() {
  form.post(route('roles.store'), {
    onSuccess: () => form.reset(),
  });
}

function startEdit(role) {
  editingId.value = role.id;
  editForm.name = role.name;
}

function cancelEdit() {
  editingId.value = null;
  editForm.reset();
}

function updateRole(role) {
  editForm.put(route('roles.update', role.id), {
    onSuccess: () => {
      editingId.value = null;
      editForm.reset();
    },
  });
}

function deleteRole(role) {
  if (confirm('Are you sure you want to delete this role?')) {
    router.delete(route('roles.destroy', role.id));
  }
}

function goTo(url) {
  if (url) router.visit(url);
}
</script> 