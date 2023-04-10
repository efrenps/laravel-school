<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    courses: {
        type: Array,
    }
});

const selectedCourseId = ref(null);

function selectCourse(courseId) {
   selectedCourseId.value = courseId;
}

function addCourse() {
    window.location= `/courses/create`
}

function editCourse() {
    window.location= `/courses/${selectedCourseId.value}`;
}

function deleteCourse() {
    const course = props.courses.find(ele => ele.id === selectedCourseId.value);

    if (course && course.students_count <= 0) {
        if (confirm("Are you sure you want to delete this course?")) {
            Inertia.delete(route("courses.destroy", selectedCourseId.value));
        }
    } else {
        alert("You cannot delete a course with enrolled students");
    }
}

const isSelectedCourse = computed(() => {
    return !selectedCourseId.value;
});
</script>

<template>
    <Head title="Courses"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Courses</h2>
        </template>
        
        <div class="flex justify-start mt-4 ml-4">
                <button 
                    class="bg-green-500 text-white py-2 px-4 rounded focus:outline-none focus:shadow-outline" 
                    type="button" 
                    @click="addCourse">Add
                </button>
                <button 
                    class="bg-blue-500 text-white ml-4 py-2 px-4 rounded focus:outline-none focus:shadow-outline" 
                    :class="{ 'bg-blue-200': isSelectedCourse }"
                    type="button" 
                    :disabled="isSelectedCourse"
                    @click="editCourse">Edit
                </button>
                <button 
                    class="bg-red-500 text-white ml-4 py-2 px-4 rounded focus:outline-none focus:shadow-outline" 
                    :class="{ 'bg-red-200': isSelectedCourse }"
                    type="button" 
                    :disabled="isSelectedCourse"
                    @click="deleteCourse">Delete
                </button>
            </div>

        <div class="flex flex-col items-center mt-6 ml-4 mr-4">
            <div class="w-full overflow-x-auto">
                <table class="table-auto border-collapse w-full px-4">
                    <thead>
                        <tr class="border-b border-gray-300">
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Start Date</th>
                            <th class="px-4 py-2 text-left">End Date</th>
                            <th class="px-4 py-2 text-left">Schedule</th>
                            <th class="px-4 py-2 text-left">Students</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="course in courses" :key="course.id" @click="selectCourse(course.id)" @dblclick="editCourse()" :class="{ 'bg-blue-200': selectedCourseId === course.id }" class="border-b border-gray-300 cursor-pointer">
                            <td class="px-4 py-2 items-center">{{ course.name }}</td>
                            <td class="px-4 py-2 items-center">{{ course.start_date }}</td>
                            <td class="px-4 py-2 items-center">{{ course.end_date }}</td>
                            <td class="px-4 py-2 items-center">{{ course.schedule }}</td>
                            <td class="px-4 py-2 items-center">{{ course.students_count }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>



