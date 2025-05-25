<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    isOpen: {
        type: Boolean,
        required: true
    }
});
</script>

<template>
    <div 
        class="fixed top-16 left-0 h-[calc(100vh-4rem)] w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out z-40"
        :class="isOpen ? 'translate-x-0' : '-translate-x-full'"
    >
        <div class="p-9">
            <div class="text-2xl font-bold text-indigo-600 mb-8">
            </div>
            <nav class="space-y-4">
                <Link :href="route('dashboard')" class="flex items-center gap-3 text-gray-700 hover:bg-indigo-50 p-4 rounded-lg transition-colors">
                    <i class="bi bi-house-fill text-lg"></i>
                    <span>Dashboard</span>
                </Link>
                <Link href="#" class="flex items-center gap-3 text-gray-700 hover:bg-indigo-50 p-4 rounded-lg transition-colors">
                    <i class="bi bi-book-fill text-lg"></i>
                    <span>Courses</span>
                </Link>
                <Link href="#" class="flex items-center gap-3 text-gray-700 hover:bg-indigo-50 p-4 rounded-lg transition-colors">
                    <i class="bi bi-file-earmark-text-fill text-lg"></i>
                    <span>Assignments</span>
                </Link>
            </nav>
        </div>
    </div>
</template> 