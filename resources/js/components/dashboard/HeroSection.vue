<script setup>
// No props or setup needed for this component
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <!-- Left Content -->
        <div>
            <p class="text-sm text-green-500 font-semibold mb-2">
                <i class="bi bi-check-circle-fill text-lg"></i> Leading Educational Platform
            </p>
            <h1 class="text-4xl font-bold mb-4">
                Transform Your Learning<br />Journey Today
            </h1>
            <p class="text-gray-600 mb-6">
                Access world-class education with our comprehensive learning platform. 
                From interactive courses to expert-led workshops, we provide the tools 
                you need to excel in your academic journey.
            </p>
            <div class="flex items-center gap-4">
                <button
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-md font-medium flex items-center"
                >
                    <i class="bi bi-person-plus-fill text-lg mr-2"></i>Start Learning
                </button>
                <button class="flex items-center text-indigo-600 hover:underline">
                    <i class="bi bi-play-circle-fill text-lg mr-2"></i>View Courses
                </button>
            </div>
        </div>

        <!-- Right Image -->
        <div class="relative">
            <img
                src="@/assets/images/elfanna.jpg"
                alt="Student Learning"
                class="rounded-lg shadow-lg"
            />

            <!-- Overlays -->
            <div
                class="absolute top-4 right-4 bg-white shadow px-3 py-2 rounded-md text-sm flex items-center"
            >
                <i class="bi bi-mortarboard-fill text-lg mr-1"></i>Expert Educators
            </div>
            <div
                class="absolute bottom-4 left-4 bg-white shadow px-3 py-2 rounded-md text-sm flex items-center"
            >
                <i class="bi bi-graph-up text-lg mr-1"></i>Learning Progress
            </div>
        </div>
    </div>
</template> 