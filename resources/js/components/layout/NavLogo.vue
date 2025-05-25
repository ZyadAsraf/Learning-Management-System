<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div class="flex shrink-0 items-center">
        <Link :href="route('dashboard')">
            <img
                src="@/assets/images/Image.png"
                alt="Logo"
                class="block h-9 w-auto"
            />
        </Link>
    </div>
</template>