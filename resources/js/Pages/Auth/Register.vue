<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import logo from '@/assets/images/Image.png';
import universityBg from '@/assets/images/universityphoto-jpeg.webp';

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <div
        class="min-h-screen flex items-center justify-center"
        :style="`background: linear-gradient(rgba(21,57,91,0.8),rgba(21,57,91,0.8)), url('${universityBg}') center center / cover no-repeat fixed;`"
    >
        <div class="w-full max-w-sm p-8 rounded-2xl shadow-2xl bg-white relative overflow-hidden" style="box-shadow: 0 8px 40px 0 rgba(24,154,180,0.15);">
            <!-- Blue crystal/glow top-right -->
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#189ab4] opacity-30 rounded-full blur-2xl z-0"></div>
            <!-- Blue crystal/glow bottom-left -->
            <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-[#189ab4] opacity-20 rounded-full blur-2xl z-0"></div>
            <div class="absolute right-4 top-4 z-10">
                <img :src="logo" alt="Logo" class="w-10 h-10 object-contain rounded-full shadow" />
            </div>
            <Head title="Register" />
            <h1 class="text-4xl font-bold text-[#189ab4] text-center mb-6 z-10">Register</h1>
            <form @submit.prevent="submit" class="space-y-6">
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#bdbdbd]">
                        <!-- User icon -->
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 4-7 8-7s8 3 8 7"/></svg>
                    </span>
                    <input
                        id="name"
                        type="text"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Name"
                        class="pl-10 pr-4 py-3 w-full rounded-lg bg-white text-gray-800 border border-[#bdbdbd] focus:ring-2 focus:ring-[#189ab4] placeholder-[#bdbdbd] shadow-none"
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#bdbdbd]">
                        <!-- Email icon -->
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><polyline points="22,7 12,13 2,7"/></svg>
                    </span>
                    <input
                        id="email"
                        type="email"
                        v-model="form.email"
                        required
                        autocomplete="username"
                        placeholder="Email"
                        class="pl-10 pr-4 py-3 w-full rounded-lg bg-white text-gray-800 border border-[#bdbdbd] focus:ring-2 focus:ring-[#189ab4] placeholder-[#bdbdbd] shadow-none"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#bdbdbd]">
                        <!-- Lock icon -->
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </span>
                    <input
                        id="password"
                        type="password"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                        placeholder="Password"
                        class="pl-10 pr-4 py-3 w-full rounded-lg bg-white text-gray-800 border border-[#bdbdbd] focus:ring-2 focus:ring-[#189ab4] placeholder-[#bdbdbd] shadow-none"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#bdbdbd]">
                        <!-- Lock icon -->
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </span>
                    <input
                        id="password_confirmation"
                        type="password"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Confirm Password"
                        class="pl-10 pr-4 py-3 w-full rounded-lg bg-white text-gray-800 border border-[#bdbdbd] focus:ring-2 focus:ring-[#189ab4] placeholder-[#bdbdbd] shadow-none"
                    />
                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full py-3 rounded-lg bg-[#189ab4] hover:bg-[#1687a7] text-white font-bold text-lg tracking-wider transition disabled:opacity-50 shadow-none"
                >
                    Register
                </button>
                <div class="text-center text-gray-500 text-sm mt-4">
                    Already have an account?
                    <Link :href="route('login')" class="text-[#189ab4] font-semibold hover:underline ml-1">Log in!</Link>
                </div>
            </form>
        </div>
    </div>
</template>
