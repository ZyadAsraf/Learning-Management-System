<script setup>
import Checkbox from '@/components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import logo from '@/assets/images/Image.png';
import universityBg from '@/assets/images/universityphoto-jpeg.webp';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = async () => {
    let token;
    let user;

    try {
        // Normal session-based login (Inertia)
        form.post(route('login'), {
            onFinish: () => form.reset('password'),
        });

        // API-based login (token-based)
        const response = await axios.post('/api/login', {
            email: form.email,
            password: form.password,
        });

        token = response.data.token;
        user = response.data.user;
    } catch (error) {
        console.error('Login error:', error);
    } finally {
        if (token && user) {
            localStorage.setItem('token', token);
            localStorage.setItem('user', JSON.stringify(user));
        }
    }
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
            <Head title="Log in" />
            <h1 class="text-4xl font-bold text-[#189ab4] text-center mb-6 z-10">Log in</h1>
            <div v-if="status" class="mb-4 text-sm font-medium text-green-500 text-center">
                {{ status }}
            </div>
            <form @submit.prevent="submit" class="space-y-6">
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#bdbdbd]">
                        <!-- User icon -->
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 4-7 8-7s8 3 8 7"/></svg>
                    </span>
                    <input
                        id="email"
                        type="email"
                        v-model="form.email"
                        required
                        autofocus
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
                        autocomplete="current-password"
                        placeholder="Password"
                        class="pl-10 pr-4 py-3 w-full rounded-lg bg-white text-gray-800 border border-[#bdbdbd] focus:ring-2 focus:ring-[#189ab4] placeholder-[#bdbdbd] shadow-none"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>
                <div class="flex items-center justify-between mt-2">
                    <label class="flex items-center text-gray-600 text-sm">
                        <Checkbox name="remember" v-model:checked="form.remember" class="mr-2" />
                        Remember me
                    </label>
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm text-[#189ab4] hover:text-[#005f73] transition underline"
                    >
                        Forgot your password?
                    </Link>
                </div>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full py-3 rounded-lg bg-[#189ab4] hover:bg-[#1687a7] text-white font-bold text-lg tracking-wider transition disabled:opacity-50 shadow-none"
                >
                    Log in
                </button>
                <div class="text-center text-gray-500 text-sm mt-4">
                    No account?
                    <Link :href="route('register')" class="text-[#189ab4] font-semibold hover:underline ml-1">Create one!</Link>
                </div>
            </form>
        </div>
    </div>
</template>
