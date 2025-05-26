<script setup>
import { Head, Link } from "@inertiajs/vue3";
import WelcomeNavBar from "@/components/WelcomeNavBar.vue";
import { ref, onMounted } from "vue";

const courses = ref([]);
const loading = ref(true);
const error = ref(null);

const fetchCourses = async () => {
    try {
        loading.value = true;
        const response = await fetch("/api/courses", {
            method: "GET",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
            credentials: "same-origin",
        });

        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            throw new Error(errorData.message || "Failed to fetch courses");
        }

        const data = await response.json();
        courses.value = Array.isArray(data) ? data : [];
    } catch (e) {
        console.error("Error fetching courses:", e);
        error.value = e.message || "An error occurred while fetching courses";
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchCourses();
});

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById("screenshot-container")?.classList.add("!hidden");
    document.getElementById("docs-card")?.classList.add("!row-span-1");
    document.getElementById("docs-card-content")?.classList.add("!flex-row");
    document.getElementById("background")?.classList.add("!hidden");
}
</script>

<template>
    <Head title="Welcome to Learning Management System" />
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
        <!-- Navigation -->
        <WelcomeNavBar />

        <!-- Hero Section -->
        <div class="relative pt-24 pb-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center"
                >
                    <div class="text-center lg:text-left">
                        <h1
                            class="text-4xl sm:text-5xl font-extrabold text-gray-900 mb-6"
                        >
                            Transform Your Learning Journey
                        </h1>
                        <p class="text-xl text-gray-600 mb-8">
                            Experience a modern learning platform designed to
                            help you achieve your educational goals.
                        </p>
                        <div
                            class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start"
                        >
                            <Link
                                :href="route('register')"
                                class="px-8 py-3 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition"
                            >
                                Get Started
                            </Link>
                            <Link
                                href="#features"
                                class="px-8 py-3 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition"
                            >
                                Learn More
                            </Link>
                        </div>
                    </div>
                    <div
                        class="h-[400px] w-full flex items-center justify-center"
                    >
                        <img
                            src="/images/books.png"
                            alt="Stack of Books"
                            class="max-h-[400px] object-contain"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Courses Section -->
        <div class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">
                        Explore Our Courses
                    </h2>
                    <p class="text-xl text-gray-600">
                        Discover a wide range of courses designed to help you
                        succeed
                    </p>
                </div>

                <!-- Loading State -->
                <div
                    v-if="loading"
                    class="flex justify-center items-center py-8"
                >
                    <div
                        class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"
                    ></div>
                </div>

                <!-- Error State -->
                <div v-else-if="error" class="text-red-600 text-center py-4">
                    {{ error }}
                </div>

                <!-- Courses Grid -->
                <div
                    v-else
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                >
                    <div
                        v-for="course in courses"
                        :key="course.id"
                        class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300"
                    >
                        <div class="p-6">
                            <h3
                                class="text-xl font-semibold text-gray-900 mb-2"
                            >
                                {{ course.title }}
                            </h3>
                            <p class="text-gray-600 mb-4">
                                {{ course.description }}
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">
                                    Instructor: {{ course.teacher?.name }}
                                </span>
                                <Link
                                    :href="route('login')"
                                    class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors duration-200"
                                >
                                    Learn More
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div
                    v-if="!loading && !error && courses.length === 0"
                    class="text-center py-8 text-gray-500"
                >
                    No courses available at the moment.
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div id="features" class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">
                        Why Choose Our Platform?
                    </h2>
                    <p class="text-xl text-gray-600">
                        Discover the features that make our learning platform
                        unique
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-xl shadow-lg">
                        <div
                            class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-6"
                        >
                            <svg
                                class="w-6 h-6 text-blue-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                ></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">
                            Interactive Learning
                        </h3>
                        <p class="text-gray-600">
                            Engage with dynamic content and interactive
                            exercises designed to enhance your learning
                            experience.
                        </p>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-lg">
                        <div
                            class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-6"
                        >
                            <svg
                                class="w-6 h-6 text-blue-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                ></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">
                            Expert Instructors
                        </h3>
                        <p class="text-gray-600">
                            Learn from industry experts and experienced
                            educators who are passionate about teaching.
                        </p>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-lg">
                        <div
                            class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-6"
                        >
                            <svg
                                class="w-6 h-6 text-blue-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                ></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">
                            Secure Platform
                        </h3>
                        <p class="text-gray-600">
                            Your data and progress are protected with
                            enterprise-grade security measures.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center text-gray-500">
                    <p>Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})</p>
                    <p class="mt-2">
                        © 2024 Learning Management System. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
