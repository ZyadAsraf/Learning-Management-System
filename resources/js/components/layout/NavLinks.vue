<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();

const navLinks = ref([
    { name: 'Students', route: 'students' },
    { name: 'Teachers', route: 'teachers' },
    { name: 'Enrollments', route: 'enrollments' },
    //{ name: 'Courses', route: 'courses' },
]);

const isActive = (routeName) => {
  if (page.url.startsWith(`/${routeName}`)) return true;

  const componentName = page.component.toLowerCase();
  return componentName.includes(routeName.toLowerCase());
};
</script>

<template>
  <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
    <div class="flex items-center gap-2">
      <Link
        v-for="link in navLinks"
        :key="link.route"
        :href="route(link.route)"
        class="relative px-5 py-2 rounded-2xl border-2 border-transparent text-black bg-white font-semibold text-base flex flex-col items-center transition-all duration-200 hover:border-black hover:bg-white hover:text-black focus:border-black focus:bg-white focus:text-black group"
        :class="{ 'border-black': isActive(link.route) }"
      >
        {{ link.name }}
        <span
          class="block h-[3px] w-6 bg-black rounded-full mt-1 transition-transform duration-200"
          :class="[isActive(link.route) ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100 group-focus:scale-x-100']"
        ></span>
      </Link>
    </div>
  </div>
</template>
