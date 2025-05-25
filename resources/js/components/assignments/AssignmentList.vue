<template>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Assignments</h2>
                        <button v-if="canCreate" 
                                @click="showCreateModal = true"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                            Create Assignment
                        </button>
                    </div>
                    
                    <div v-if="assignments.length === 0" class="text-center py-4">
                        <p class="text-gray-500">No assignments found.</p>
                    </div>

                    <div v-else class="space-y-4">
                        <div v-for="assignment in assignments" :key="assignment.id" 
                             class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-medium">{{ assignment.title }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ assignment.course.title }}</p>
                                    <p class="text-sm text-gray-500 mt-2">{{ assignment.description }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium" :class="{
                                        'text-red-600': isOverdue(assignment.due_date),
                                        'text-green-600': !isOverdue(assignment.due_date)
                                    }">
                                        Due: {{ formatDate(assignment.due_date) }}
                                    </p>
                                    <p v-if="assignment.submissions?.length > 0" class="text-sm text-green-600 mt-1">
                                        Submitted
                                    </p>
                                </div>
                            </div>
                            
                            <div class="mt-4 flex justify-between items-center">
                                <div class="space-x-2">
                                    <Link :href="route('assignments.show', assignment.id)" 
                                          class="text-indigo-600 hover:text-indigo-800">
                                        View Details
                                    </Link>
                                    <a :href="route('assignments.download', assignment.id)" 
                                       class="text-indigo-600 hover:text-indigo-800">
                                        Download PDF
                                    </a>
                                </div>
                                <div v-if="canEdit" class="space-x-2">
                                    <button @click="editAssignment(assignment)" 
                                            class="text-indigo-600 hover:text-indigo-800">
                                        Edit
                                    </button>
                                    <button @click="deleteAssignment(assignment)" 
                                            class="text-red-600 hover:text-red-800">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div v-if="assignments.length > 0" class="mt-6">
                        <Pagination :links="pagination.links" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <Modal :show="showCreateModal" @close="showCreateModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    {{ editingAssignment ? 'Edit Assignment' : 'Create Assignment' }}
                </h2>

                <form @submit.prevent="submitForm" class="space-y-4">
                    <div>
                        <InputLabel for="title" value="Title" />
                        <TextInput id="title" v-model="form.title" type="text" class="mt-1 block w-full" required />
                        <InputError :message="form.errors.title" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="description" value="Description" />
                        <textarea id="description" v-model="form.description" 
                                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                  rows="4" required></textarea>
                        <InputError :message="form.errors.description" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="due_date" value="Due Date" />
                        <TextInput id="due_date" v-model="form.due_date" type="datetime-local" 
                                  class="mt-1 block w-full" required />
                        <InputError :message="form.errors.due_date" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="pdf_file" value="PDF File" />
                        <input type="file" id="pdf_file" @change="handleFileUpload" 
                               class="mt-1 block w-full" 
                               :required="!editingAssignment"
                               accept=".pdf" />
                        <InputError :message="form.errors.pdf_file" class="mt-2" />
                    </div>

                    <div class="flex justify-end space-x-2 mt-6">
                        <SecondaryButton @click="showCreateModal = false">
                            Cancel
                        </SecondaryButton>
                        <PrimaryButton type="submit" :disabled="form.processing">
                            {{ editingAssignment ? 'Update' : 'Create' }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import Modal from '@/components/Modal.vue';
import Pagination from '@/components/Pagination.vue';
import InputLabel from '@/components/InputLabel.vue';
import TextInput from '@/components/TextInput.vue';
import InputError from '@/components/InputError.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import SecondaryButton from '@/components/SecondaryButton.vue';

const props = defineProps({
    initialAssignments: {
        type: Object,
        required: true
    }
});

const assignments = ref(props.initialAssignments.data);
const pagination = ref({
    links: props.initialAssignments.links
});

const showCreateModal = ref(false);
const editingAssignment = ref(null);

const form = useForm({
    title: '',
    description: '',
    due_date: '',
    pdf_file: null,
    course_id: usePage().props.course?.id
});

const canCreate = computed(() => usePage().props.auth.user.roles.includes('teacher'));
const canEdit = computed(() => usePage().props.auth.user.roles.includes('teacher'));

const formatDate = (date) => {
    return new Date(date).toLocaleString();
};

const isOverdue = (dueDate) => {
    return new Date(dueDate) < new Date();
};

const handleFileUpload = (event) => {
    form.pdf_file = event.target.files[0];
};

const editAssignment = (assignment) => {
    editingAssignment.value = assignment;
    form.title = assignment.title;
    form.description = assignment.description;
    form.due_date = new Date(assignment.due_date).toISOString().slice(0, 16);
    showCreateModal.value = true;
};

const deleteAssignment = async (assignment) => {
    if (!confirm('Are you sure you want to delete this assignment?')) {
        return;
    }

    try {
        await axios.delete(route('assignments.destroy', assignment.id));
        assignments.value = assignments.value.filter(a => a.id !== assignment.id);
    } catch (error) {
        console.error('Failed to delete assignment:', error);
    }
};

const submitForm = async () => {
    if (editingAssignment.value) {
        await form.put(route('assignments.update', editingAssignment.value.id), {
            onSuccess: () => {
                showCreateModal.value = false;
                editingAssignment.value = null;
                form.reset();
            }
        });
    } else {
        await form.post(route('assignments.store'), {
            onSuccess: () => {
                showCreateModal.value = false;
                form.reset();
            }
        });
    }
};
</script> 