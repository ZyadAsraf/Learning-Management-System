<template>
    <AppLayout :title="enrollment.course.title">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Course Enrollment Details
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="mb-6">
                            <h3 class="text-2xl font-bold mb-2">{{ enrollment.course.title }}</h3>
                            <p class="text-gray-600">{{ enrollment.course.description }}</p>
                        </div>

                        <div class="border-t pt-6">
                            <h4 class="text-lg font-semibold mb-4">Enrollment Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Enrolled Date</p>
                                    <p class="font-medium">{{ new Date(enrollment.created_at).toLocaleDateString() }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Student</p>
                                    <p class="font-medium">{{ enrollment.student.name }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-between items-center">
                            <Link :href="route('courses.show', enrollment.course.id)" 
                                  class="text-indigo-600 hover:text-indigo-800">
                                View Course
                            </Link>
                            <button @click="unenroll" 
                                    class="text-red-600 hover:text-red-800">
                                Unenroll from Course
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    enrollment: {
        type: Object,
        required: true
    }
});

const unenroll = async () => {
    if (!confirm('Are you sure you want to unenroll from this course?')) {
        return;
    }

    try {
        await axios.delete(route('enrollments.destroy', props.enrollment.id));
        window.location.href = route('enrollments.index');
    } catch (error) {
        console.error('Failed to unenroll:', error);
    }
};
</script> 