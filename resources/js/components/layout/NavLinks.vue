<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();

// Get user from localStorage and parse it
const user = JSON.parse(localStorage.getItem('user'));

// Role-based navigation
let links = [];

if (user?.role_id === 1) {
    // Admin
    links = [
        { name: 'Students', route: 'students' },
        { name: 'Teachers', route: 'teachers' },
        { name: 'Courses', route: 'courses' },
        { name: 'Enrollments', route: 'enrollments' }
    ];
} else if (user?.role_id === 2) {
    // Teacher
    links = [
       // { name: 'Grade', route: 'grade' },
    { name: 'Upload Material', route: 'teacher.materials.upload' },
    { name: 'My Assignments', route: 'teacher.assignments.index' }

    ];
} else if (user?.role_id === 3) {
    // Student
    links = []; // No links for student
}

const navLinks = ref(links);

// Check if a link is active based on both URL path and component name
const isActive = (routeName) => {
    return page.url.startsWith(`/${routeName}`) || 
           page.component === routeName ||
           page.component === routeName.charAt(0).toUpperCase() + routeName.slice(1);
};
</script>


<template>
    <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
        <div class="flex items-center gap-2">
            <Link 
                v-for="link in navLinks" 
                :key="link.route"
                :href="route(link.route)" 
                class="relative px-5 py-2 rounded-2xl border-2 border-transparent text-black bg-white font-semibold text-base flex flex-col items-center transition-all duration-200 hover:border-black hover:bg-white hover:text-black focus:border-black focus:bg-white focus:text-black group"
                :class="{ 'border-black': isActive(link.route) }"
            >
                {{ link.name }}
                <span 
                    class="block h-[3px] w-6 bg-black rounded-full mt-1 transition-transform duration-200"
                    :class="[isActive(link.route) ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100 group-focus:scale-x-100']"
                ></span>
            </Link>
        </div>
    </div>
</template>