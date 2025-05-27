<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
      <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
          <span>{{ course.code || 'CET217-2024-2' }}</span>
          <span>/</span>
          <span>{{ assignment.number || 'Assignment 1' }}</span>
        </div>
        
        <div class="flex items-center space-x-3">
          <div class="w-12 h-12 bg-pink-500 rounded-lg flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
          <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ assignment.title || 'Assignment 1' }}</h1>
            <div class="flex items-center space-x-4 mt-1">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Done
              </span>
              <!-- Display Grade if Available -->
              <span v-if="submissionStatus.grade" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                Grade: {{ submissionStatus.grade }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-6">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Assignment Details -->
          <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center space-x-2 text-sm">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 5a9 9 0 100-18 9 9 0 000 18z" />
                  </svg>
                  <span class="font-medium">Opened:</span>
                  <span class="text-gray-600">{{ formatDate(assignment.opened) || 'Tuesday, 18 February 2025, 12:00 AM' }}</span>
                </div>
                <div class="flex items-center space-x-2 text-sm">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span class="font-medium">Due:</span>
                  <span class="text-gray-600">{{ formatDate(assignment.due_date) || 'Tuesday, 25 February 2025, 11:59 PM' }}</span>
                </div>
              </div>
              <div v-if="!loading && !error" class="pt-4">
                <p class="text-gray-700">{{ assignment.description || 'No description provided' }}</p>
              </div>
            </div>
          </div>

          <!-- Submission Form -->
          <div class="bg-white rounded-lg shadow-sm border p-6">
            <h2 class="text-lg font-semibold mb-4">This comprehensive material covers essential JavaScript concepts including variables, functions, objects, arrays, and modern ES6+ features like arrow functions, destructuring, promises, and async/await. Perfect for both beginners and intermediate developers looking to strengthen their JavaScript foundation</h2>
            
            <form @submit.prevent="submitAssignment" class="space-y-4">
              <!-- Text Submission -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Text Submission</label>
                <textarea
                  v-model="submissionText"
                  placeholder="Write your assignment submission here..."
                  rows="8"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-vertical"
                  :disabled="submission && submission.submitted_at && !isEditing"
                ></textarea>
              </div>

              <!-- File Upload Section -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">File Submission</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                  <div class="space-y-1 text-center">
                    <div class="flex text-sm text-gray-600">
                      <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                        <span>Upload a file</span>
                        <input id="file-upload" name="file-upload" type="file" class="sr-only" @change="handleFileUpload" multiple :disabled="submission && submission.submitted_at && !isEditing">
                      </label>
                      <p class="pl-1">or drag and drop</p>
                    </div>
                    <p class="text-xs text-gray-500">
                      PDF, DOC, DOCX up to 10MB
                    </p>
                  </div>
                </div>

                <!-- Uploaded Files Display -->
                <div v-if="uploadedFiles.length > 0" class="mt-4 space-y-2">
                  <div v-for="(file, index) in uploadedFiles" :key="index" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                      </svg>
                      <div>
                        <p class="text-sm font-medium text-gray-900">{{ file.name }}</p>
                        <p class="text-xs text-gray-500">{{ formatFileSize(file.size) }} • {{ formatDate(file.uploadedAt) }}</p>
                      </div>
                    </div>
                    <button 
                      @click="removeFile(index)"
                      type="button"
                      class="text-red-500 hover:text-red-700 p-1"
                      :disabled="submission && submission.submitted_at && !isEditing"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
              
              <div class="flex items-center justify-between">
                <div class="flex space-x-4">
                  <button
                    type="submit"
                    :disabled="submitting || (submission && submission.submitted_at && !isEditing) || (!submissionText.trim() && uploadedFiles.length === 0)"
                    class="flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white rounded-md font-medium transition-colors"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    {{ submitting ? 'Submitting...' : isEditing ? 'Resubmit Assignment' : 'Submit Assignment' }}
                  </button>
                  <button
                    v-if="submission && submission.submitted_at"
                    @click="editSubmission"
                    type="button"
                    class="flex items-center px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md font-medium transition-colors"
                  >
                    Edit Submission
                  </button>
                </div>
                
                <div class="text-sm text-gray-500">
                  Auto-save enabled
                </div>
              </div>
              
              <div v-if="submission && submission.submitted_at" class="text-gray-500 text-sm">
                Submitted on: {{ formatDate(submission.submitted_at) }}
              </div>
              
              <div v-if="successMessage" class="p-3 bg-green-50 border border-green-200 rounded-md flex items-center justify-between">
                <p class="text-sm text-green-700">{{ successMessage }}</p>
                <button
                  type="button"
                  class="text-green-700 hover:text-green-900"
                  @click="successMessage = null"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              
              <div v-if="errorMessage" class="p-3 bg-red-50 border border-red-200 rounded-md">
                <p class="text-sm text-red-700">{{ errorMessage }}</p>
              </div>
            </form>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
          <!-- Instructor Feedback and Grade -->
          <div v-if="instructorFeedback || submissionStatus.grade" class="bg-white rounded-lg shadow-sm border p-6">
            <h2 class="text-lg font-semibold mb-4">Instructor Feedback & Grade</h2>
            
            <div class="space-y-3">
              <div class="p-3 bg-blue-50 border-l-4 border-blue-400 rounded">
                <div class="flex items-start">
                  <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </div>
                  <div class="ml-3">
                    <div class="text-sm font-medium text-blue-800">
                      {{ instructorFeedback?.instructor_name || 'Soliman Younis' }}
                    </div>
                    <div v-if="instructorFeedback" class="text-sm text-blue-700 mt-1">
                      {{ instructorFeedback.feedback || 'No feedback provided' }}
                    </div>
                    <div v-if="submissionStatus.grade" class="text-sm text-blue-700 mt-1">
                      Grade: {{ submissionStatus.grade }} (Graded on {{ formatDate(submissionStatus.graded_at) || 'Friday, May 16, 2025, 2:22 PM' }})
                    </div>
                    <div class="text-xs text-blue-600 mt-2">
                      {{ formatDate(instructorFeedback?.created_at || submissionStatus.graded_at) || 'Friday, May 16, 2025, 2:22 PM' }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- File Submissions -->
          <div v-if="submittedFiles.length > 0" class="bg-white rounded-lg shadow-sm border p-6">
            <h2 class="text-lg font-semibold mb-4">Submitted Files</h2>
            
            <div class="space-y-2">
              <div v-for="(file, index) in submittedFiles" :key="index" class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <div class="flex-1">
                  <div class="text-sm font-medium text-purple-600 hover:text-purple-800 cursor-pointer" @click="downloadFile(file)">
                    {{ file.name }}
                  </div>
                  <div class="text-xs text-gray-500">
                    {{ formatFileSize(file.size) }} • {{ formatDate(file.uploaded_at) }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from 'axios';

export default {
  name: 'AssignmentView',
  props: {
    course_id: {
      type: [String, Number],
      required: true,
    },
    assignment_id: {
      type: [String, Number],
      required: true,
    },
  },
  data() {
    return {
      course: {
        title: '',
        code: 'CET217-2024-2'
      },
      assignment: {
        title: 'Ass-ignment 1',
        number: 'Assignment 1',
        description: 'Submit the assignment of Lab 1 in PDF file',
        due_date: '',
        opened: '',
      },
      submission: null,
      submissionText: '',
      uploadedFiles: [],
      submittedFiles: [],
      submissionStatus: {
        status: 'Submitted for grading',
        grading: 'Graded',
        timeRemaining: 'Assignment was submitted 3 days 12 hours early',
        lastModified: '',
        grade: '5.00/5.00',
        graded_at: '2025-05-16T14:22:00',
        graded_by: 'Soliman Younis'
      },
      instructorFeedback: {
        instructor_name: 'Soliman Younis',
        feedback: 'Excellent work! Your submission demonstrates a strong understanding of JavaScript concepts.',
        created_at: '2025-05-16T14:22:00'
      },
      loading: true,
      error: null,
      successMessage: null,
      errorMessage: null,
      submitting: false,
      isEditing: false,
    };
  },
  mounted() {
    this.fetchAssignmentData();
  },
  methods: {
    async fetchAssignmentData() {
      this.loading = true;
      this.error = null;
      try {
        const token = localStorage.getItem('token');
        if (token) axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        
        const response = await axios.get(`/api/assignments/${this.assignment_id}/submission`);
        const data = response.data;

        this.course.title = data.course.title;
        this.course.code = data.course.code || this.course.code;
        this.assignment = {
          title: data.assignment.title,
          number: data.assignment.number || 'Assignment 1',
          description: data.assignment.description,
          due_date: data.assignment.due_date,
          opened: data.assignment.opened,
        };
        this.submission = data.submission;
        if (this.submission) {
          this.submissionText = this.submission.content;
          this.submissionStatus = {
            ...this.submissionStatus,
            ...data.submissionStatus,
            grade: data.submissionStatus?.grade || this.submissionStatus.grade,
            graded_at: data.submissionStatus?.graded_at || this.submissionStatus.graded_at,
            graded_by: data.submissionStatus?.graded_by || this.submissionStatus.graded_by
          };
        }
        
        // Load submitted files and instructor feedback
        this.submittedFiles = data.submittedFiles || [];
        this.instructorFeedback = data.instructorFeedback || this.instructorFeedback;
        
      } catch (error) {
        console.error('Error loading assignment data:', error);
        if (error.response) {
          if (error.response.status === 403) {
            this.error = 'You are not authorized to view this assignment.';
          } else if (error.response.status === 404) {
            this.error = 'Assignment not found.';
          } else {
            this.error = error.response.data.error || 'Failed to connect to the server. Please try again.';
          }
        } else {
          this.error = 'Failed to connect to the server. Please try again.';
        }
      } finally {
        this.loading = false;
      }
    },
    
    handleFileUpload(event) {
      const files = Array.from(event.target.files);
      files.forEach(file => {
        if (file.size > 10 * 1024 * 1024) { // 10MB limit
          this.errorMessage = `File ${file.name} is too large. Maximum size is 10MB.`;
          return;
        }
        
        const fileObj = {
          name: file.name,
          size: file.size,
          type: file.type,
          file: file,
          uploadedAt: new Date()
        };
        
        this.uploadedFiles.push(fileObj);
      });
      
      // Clear the input
      event.target.value = '';
    },
    
    removeFile(index) {
      this.uploadedFiles.splice(index, 1);
    },
    
    async submitAssignment() {
      if (!this.submissionText.trim() && this.uploadedFiles.length === 0) {
        this.errorMessage = 'Please provide either text submission or upload files.';
        return;
      }
      
      this.errorMessage = null;
      this.successMessage = null;
      this.submitting = true;
      
      try {
        const token = localStorage.getItem('token');
        if (token) axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        
        const formData = new FormData();
        formData.append('submission_text', this.submissionText);
        
        // Add files to form data
        this.uploadedFiles.forEach((fileObj, index) => {
          formData.append(`files[${index}]`, fileObj.file);
        });
        
        await axios.post(`/api/student/course/${this.course_id}/assignment/${this.assignment_id}/submit`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });
        
        this.successMessage = 'Assignment submitted successfully!';
        this.uploadedFiles = []; // Clear uploaded files after submission
        this.isEditing = false;
        
        // Refresh submission data
        await this.fetchAssignmentData();
        
      } catch (error) {
        console.error('Error submitting assignment:', error);
        this.errorMessage = error.response?.data?.error || 'Failed to submit assignment. Please try again.';
      } finally {
        this.submitting = false;
      }
    },
    
    async removeSubmission() {
      if (confirm('Are you sure you want to remove this submission? This action cannot be undone.')) {
        try {
          const token = localStorage.getItem('token');
          if (token) axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
          
          await axios.delete(`/api/student/course/${this.course_id}/assignment/${this.assignment_id}/submission`);
          
          this.successMessage = 'Submission removed successfully!';
          this.submissionText = '';
          this.uploadedFiles = [];
          this.submittedFiles = [];
          this.submission = null;
          this.isEditing = false;
          
          await this.fetchAssignmentData();
        } catch (error) {
          console.error('Error removing submission:', error);
          this.errorMessage = error.response?.data?.error || 'Failed to remove submission. Please try again.';
        }
      }
    },
    
    editSubmission() {
      if (this.submission && this.submission.submitted_at) {
        if (confirm('Editing will withdraw your current submission. Are you sure you want to continue?')) {
          this.isEditing = true;
          this.successMessage = 'You can now edit your submission. Remember to resubmit when finished.';
        }
      }
    },
    
    downloadFile(file) {
      const link = document.createElement('a');
      link.href = `/api/student/files/${file.id}/download`;
      link.download = file.name;
      link.click();
    },
    
    formatDate(date) {
      if (!date) return 'No date available';
      try {
        return new Date(date).toLocaleDateString('en-US', {
          year: 'numeric',
          month: 'long',
          day: 'numeric',
          hour: '2-digit',
          minute: '2-digit',
        });
      } catch (e) {
        return 'Invalid date';
      }
    },
    
    formatFileSize(bytes) {
      if (bytes === 0) return '0 Bytes';
      const k = 1024;
      const sizes = ['Bytes', 'KB', 'MB', 'GB'];
      const i = Math.floor(Math.log(bytes) / Math.log(k));
      return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    },
  },
};
</script>

<style scoped>
.resize-vertical {
  resize: vertical;
}

/* File upload area styling */
input[type="file"] {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border: 0;
}
</style>