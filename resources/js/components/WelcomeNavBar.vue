<template>
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <img
                            class="block h-8 w-auto"
                            src="/images/image.png"
                            alt="Logo"
                        />
                    </div>
                    <div class="ml-3">
                        <h1 class="text-gray-900 text-xl font-bold">
                            Learning Management System
                        </h1>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center md:hidden">
                    <button
                        @click="isOpen = !isOpen"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-gray-900 focus:outline-none"
                    >
                        <svg
                            class="h-6 w-6"
                            :class="{ hidden: isOpen, block: !isOpen }"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>
                        <svg
                            class="h-6 w-6"
                            :class="{ block: isOpen, hidden: !isOpen }"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Desktop menu -->
                <div class="hidden md:flex md:items-center">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <Link
                            :href="route('login')"
                            class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out"
                        >
                            Log in
                        </Link>
                        <Link
                            :href="route('register')"
                            class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out"
                        >
                            Register
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div class="md:hidden" :class="{ block: isOpen, hidden: !isOpen }">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <Link
                        :href="route('login')"
                        class="text-gray-600 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium transition duration-150 ease-in-out"
                    >
                        Log in
                    </Link>
                    <Link
                        :href="route('register')"
                        class="bg-indigo-600 text-white hover:bg-indigo-700 block px-3 py-2 rounded-md text-base font-medium transition duration-150 ease-in-out"
                    >
                        Register
                    </Link>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";

const isOpen = ref(false);
</script>
