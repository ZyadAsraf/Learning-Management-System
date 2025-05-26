<template>
    <AppLayout title="Assignments">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Assignments
            </h2>
        </template>

        <AssignmentList :initial-assignments="assignments" />
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import AssignmentList from "@/components/assignments/AssignmentList.vue";

defineProps({
    assignments: {
        type: Object,
        required: true,
    },
});
</script>
