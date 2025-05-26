<script setup>
import { ref } from "vue";
import NavBar from "@/components/layout/NavBar.vue";
import Sidebar from "@/components/layout/Sidebar.vue";

const isSliderOpen = ref(false);

const toggleSlider = () => {
  isSliderOpen.value = !isSliderOpen.value;
};
</script>

<template>
    <div class="w-full">
        <div class="min-h-screen bg-white w-full">
            <NavBar 
                :user="$page.props.auth.user"
                @toggle-slider="toggleSlider"
            />
            
            <Sidebar :is-open="isSliderOpen" />

            <!-- Page Heading -->
            <header class="bg-white shadow mt-16" v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="mt-16">
                <slot />
            </main>
        </div>
    </div>
</template>
