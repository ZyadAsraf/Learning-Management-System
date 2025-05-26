<template>
  <AppLayout title="Roles">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Roles
      </h2>
    </template>
    <RoleList :roles="roles" />
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import RoleList from '@/Components/roles/RoleList.vue';

defineProps({
  roles: {
    type: Object,
    required: true
  }
});
</script> 