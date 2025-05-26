<template>
    <AppLayout :title="assignment.title">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Assignment Details
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="mb-6">
                            <h3 class="text-2xl font-bold mb-2">{{ assignment.title }}</h3>
                            <p class="text-gray-600">{{ assignment.description }}</p>
                        </div>

                        <div class="border-t pt-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Course</p>
                                    <p class="font-medium">{{ assignment.course.title }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Due Date</p>
                                    <p class="font-medium" :class="{
                                        'text-red-600': isOverdue(assignment.due_date),
                                        'text-green-600': !isOverdue(assignment.due_date)
                                    }">
                                        {{ formatDate(assignment.due_date) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a :href="route('assignments.download', assignment.id)" 
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Download Assignment PDF
                            </a>
                        </div>

                        <!-- Submission Section -->
                        <div v-if="isStudent" class="mt-8 border-t pt-6">
                            <h4 class="text-lg font-semibold mb-4">Your Submission</h4>
                            
                            <div v-if="assignment.submissions?.length > 0" class="bg-green-50 p-4 rounded-lg">
                                <p class="text-green-700">You have already submitted this assignment.</p>
                                <p class="text-sm text-green-600 mt-2">
                                    Submitted on: {{ formatDate(assignment.submissions[0].created_at) }}
                                </p>
                            </div>
                            
                            <form v-else @submit.prevent="submitAssignment" class="mt-4">
                                <div>
                                    <InputLabel for="submission_file" value="Upload Your Solution" />
                                    <input type="file" id="submission_file" @change="handleFileUpload" 
                                           class="mt-1 block w-full" required accept=".pdf,.doc,.docx" />
                                    <InputError :message="form.errors.submission_file" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <PrimaryButton type="submit" :disabled="form.processing">
                                        Submit Assignment
                                    </PrimaryButton>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputLabel from '@/components/InputLabel.vue';
import InputError from '@/components/InputError.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';

const props = defineProps({
    assignment: {
        type: Object,
        required: true
    }
});

const form = useForm({
    submission_file: null
});

const isStudent = computed(() => usePage().props.auth.user.roles.includes('student'));

const formatDate = (date) => {
    return new Date(date).toLocaleString();
};

const isOverdue = (dueDate) => {
    return new Date(dueDate) < new Date();
};

const handleFileUpload = (event) => {
    form.submission_file = event.target.files[0];
};

const submitAssignment = async () => {
    if (isOverdue(props.assignment.due_date)) {
        if (!confirm('This assignment is overdue. Are you sure you want to submit?')) {
            return;
        }
    }

    await form.post(route('submissions.store', props.assignment.id), {
        onSuccess: () => {
            form.reset();
        }
    });
};
</script> 