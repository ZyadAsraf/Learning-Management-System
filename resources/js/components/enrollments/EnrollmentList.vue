<template>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-4">My Enrollments</h2>
                    
                    <div v-if="enrollments.length === 0" class="text-center py-4">
                        <p class="text-gray-500">You are not enrolled in any courses yet.</p>
                    </div>

                    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div v-for="enrollment in enrollments" :key="enrollment.id" 
                             class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                            <h3 class="text-lg font-medium mb-2">{{ enrollment.course.title }}</h3>
                            <p class="text-gray-600 mb-2">{{ enrollment.course.description }}</p>
                            <div class="flex justify-between items-center mt-4">
                                <Link :href="route('courses.show', enrollment.course.id)" 
                                      class="text-indigo-600 hover:text-indigo-800">
                                    View Course
                                </Link>
                                <button @click="unenroll(enrollment.id)" 
                                        class="text-red-600 hover:text-red-800">
                                    Unenroll
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div v-if="enrollments.length > 0" class="mt-6">
                        <Pagination :links="pagination.links" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    initialEnrollments: {
        type: Object,
        required: true
    }
});

const enrollments = ref(props.initialEnrollments.data);
const pagination = ref({
    links: props.initialEnrollments.links
});

const unenroll = async (enrollmentId) => {
    if (!confirm('Are you sure you want to unenroll from this course?')) {
        return;
    }

    try {
        await axios.delete(route('enrollments.destroy', enrollmentId));
        enrollments.value = enrollments.value.filter(e => e.id !== enrollmentId);
    } catch (error) {
        console.error('Failed to unenroll:', error);
    }
};
</script> 