<script setup>
import { Link } from "@inertiajs/vue3";
import NavToggle from "./NavToggle.vue";
import NavLogo from "./NavLogo.vue";
import NavLinks from "./NavLinks.vue";
import UserProfile from "./UserProfile.vue";

defineProps({
    user: {
        type: Object,
        required: true
    }
});

defineEmits(['toggleSlider']);

const handleToggleSlider = () => {
    emit('toggleSlider');
};
</script>

<template>
    <nav class="fixed top-0 right-0 left-0 bg-white border-b border-gray-200 z-30 w-full">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center w-full justify-between relative">
                <!-- Left side: Slider Button and Logo -->
                <div class="flex items-center gap-4">
                    <NavToggle @toggle="$emit('toggleSlider')" />
                    <NavLogo />
                </div>
                
                <!-- Center: Navigation Links -->
                <NavLinks />
                
                <!-- Right side: User Profile -->
                <UserProfile :user="user" />
            </div>
        </div>
    </nav>
</template>