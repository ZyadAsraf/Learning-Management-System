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

// Define default profile photo URL as a constant
const DEFAULT_PHOTO_URL = 'https://t3.ftcdn.net/jpg/06/95/65/68/360_F_695656801_QevfwycFSPRP8HKGYKXOTLbJib5BPZSl.jpg';

// Define dropdown menu items as an array
const menuItems = [
    { name: 'Profile', route: 'profile.edit', method: 'get' },
    { name: 'Log Out', route: 'logout', method: 'post' }
];
</script>

<template>
    <div class="hidden sm:flex sm:items-center gap-4">
        <img
            :src="user.profile_photo_url || DEFAULT_PHOTO_URL"
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
                    <DropdownLink 
                        v-for="item in menuItems"
                        :key="item.name"
                        :href="route(item.route)" 
                        :method="item.method" 
                        :as="item.method === 'post' ? 'button' : undefined"
                    >
                        {{ item.name }}
                    </DropdownLink>
                </template>
            </Dropdown>
        </div>
    </div>
</template>