<template>
    <div>
        <button v-if="!isEnrolled" 
                @click="enroll" 
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                :disabled="isLoading">
            <span v-if="isLoading">Enrolling...</span>
            <span v-else>Enroll in Course</span>
        </button>
        
        <button v-else 
                @click="unenroll" 
                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                :disabled="isLoading">
            <span v-if="isLoading">Unenrolling...</span>
            <span v-else>Unenroll from Course</span>
        </button>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    courseId: {
        type: Number,
        required: true
    },
    initialEnrolled: {
        type: Boolean,
        default: false
    }
});

const isEnrolled = ref(props.initialEnrolled);
const isLoading = ref(false);

const enroll = async () => {
    if (isLoading.value) return;
    
    isLoading.value = true;
    try {
        await axios.post(route('enrollments.store'), {
            course_id: props.courseId
        });
        isEnrolled.value = true;
    } catch (error) {
        console.error('Failed to enroll:', error);
    } finally {
        isLoading.value = false;
    }
};

const unenroll = async () => {
    if (isLoading.value) return;
    
    if (!confirm('Are you sure you want to unenroll from this course?')) {
        return;
    }

    isLoading.value = true;
    try {
        const enrollment = usePage().props.enrollments.find(e => e.course_id === props.courseId);
        if (enrollment) {
            await axios.delete(route('enrollments.destroy', enrollment.id));
            isEnrolled.value = false;
        }
    } catch (error) {
        console.error('Failed to unenroll:', error);
    } finally {
        isLoading.value = false;
    }
};
</script> 