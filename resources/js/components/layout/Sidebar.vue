<script setup>
import { Link } from "@inertiajs/vue3";
import { computed } from 'vue';
import SidebarLink from '@/components/layout/SidebarLink.vue';
const props = defineProps({
    isOpen: {
        type: Boolean,
        required: true
    }
});

// Sidebar navigation links as dynamic data
const navItems = [
    { name: 'Dashboard', icon: 'bi-house-fill', route: 'dashboard' },
    { name: 'Courses', icon: 'bi-book-fill', route: '#' },
    { name: 'Assignments', icon: 'bi-file-earmark-text-fill', route: '#' }
];

// Computed class for the sidebar based on open state
const sidebarClass = computed(() => {
    return props.isOpen ? 'translate-x-0' : '-translate-x-full';
});
</script>

<template>
    <div 
        class="fixed top-16 left-0 h-[calc(100vh-4rem)] w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out z-40"
        :class="sidebarClass"
    >
        <div class="p-9">
            <div class="text-2xl font-bold text-indigo-600 mb-8"></div>
            <nav class="space-y-4">
                <SidebarLink 
                    v-for="item in navItems" 
                    :key="item.name"
                    :href="item.route.startsWith('#') ? item.route : route(item.route)"
                >
                    <i :class="[item.icon, 'text-lg']"></i>
                    <span>{{ item.name }}</span>
                </SidebarLink>
            </nav>
        </div>
    </div>
</template>