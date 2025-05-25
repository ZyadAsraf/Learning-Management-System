<script setup>
import { Link } from "@inertiajs/vue3";
import Dropdown from "@/components/Dropdown.vue";
import DropdownLink from "@/components/DropdownLink.vue";

defineProps({
    user: {
        type: Object,
        required: true
    }
});

defineEmits(['toggleSlider']);
</script>

<template>
    <nav class="fixed top-0 right-0 left-0 bg-white border-b border-gray-200 z-30 w-full">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center w-full justify-between relative">
                <!-- Slider Button and Logo (left) -->
                <div class="flex items-center gap-4">
                    <button 
                        @click="$emit('toggleSlider')"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-indigo-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 transition-colors"
                    >
                        <i class="bi bi-list text-xl"></i>
                    </button>
                    <div class="flex shrink-0 items-center">
                        <Link :href="route('dashboard')">
                            <img
                                src="@/assets/images/Image.png"
                                alt="Logo"
                                class="block h-9 w-auto"
                            />
                        </Link>
                    </div>
                </div>
                <!-- Navigation Buttons (centered absolutely) -->
                <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
                    <div class="flex items-center gap-2">
                        <Link :href="route('students')" class="relative px-5 py-2 rounded-2xl border-2 border-transparent text-black bg-white font-semibold text-base flex flex-col items-center transition-all duration-200 hover:border-black hover:bg-white hover:text-black focus:border-black focus:bg-white focus:text-black group">
                            Students
                            <span class="block h-[3px] w-6 bg-black rounded-full mt-1 scale-x-0 group-hover:scale-x-100 group-focus:scale-x-100 transition-transform duration-200"></span>
                        </Link>
                        <Link :href="route('teachers')" class="relative px-5 py-2 rounded-2xl border-2 border-transparent text-black bg-white font-semibold text-base flex flex-col items-center transition-all duration-200 hover:border-black hover:bg-white hover:text-black focus:border-black focus:bg-white focus:text-black group">
                            Teachers
                            <span class="block h-[3px] w-6 bg-black rounded-full mt-1 scale-x-0 group-hover:scale-x-100 group-focus:scale-x-100 transition-transform duration-200"></span>
                        </Link>
                        <Link :href="route('enrollments')" class="relative px-5 py-2 rounded-2xl border-2 border-transparent text-black bg-white font-semibold text-base flex flex-col items-center transition-all duration-200 hover:border-black hover:bg-white hover:text-black focus:border-black focus:bg-white focus:text-black group">
                            Enrollments
                            <span class="block h-[3px] w-6 bg-black rounded-full mt-1 scale-x-0 group-hover:scale-x-100 group-focus:scale-x-100 transition-transform duration-200"></span>
                        </Link>
                    </div>
                </div>
                <!-- Profile (right) -->
                <div class="hidden sm:flex sm:items-center gap-4">
                    <img
                        :src="user.profile_photo_url || 'https://t3.ftcdn.net/jpg/06/95/65/68/360_F_695656801_QevfwycFSPRP8HKGYKXOTLbJib5BPZSl.jpg'"
                        alt="Profile"
                        class="w-9 h-9 rounded-full border-2 border-indigo-400 object-cover"
                    />
                    <div class="relative ms-3">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-700 transition duration-150 ease-in-out hover:text-indigo-600 focus:outline-none"
                                    >
                                        {{ user.name }}
                                        <svg
                                            class="-me-0.5 ms-2 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </button>
                                </span>
                            </template>
                            <template #content>
                                <DropdownLink :href="route('profile.edit')">
                                    Profile
                                </DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">
                                    Log Out
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template> 