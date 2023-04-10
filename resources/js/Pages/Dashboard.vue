<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import CourseCard from '@/Pages/CourseCard.vue';

const props = defineProps({
    courses: {
        type: Array,
    }
});

</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="container mx-auto py-8">
            <h1 class="text-2xl font-semibold mb-4">Our top 3 courses with the highest number of students in the last 6 months</h1>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <CourseCard v-for="course in courses" :key="course.id" :course="course" />
            </div>
        </div>

    </AuthenticatedLayout>
</template>
