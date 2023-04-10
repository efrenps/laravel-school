<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    students: {
        type: Object,
    }
});

const selectedStudentId = ref(null);

function selectStudent(courseId) {
   selectedStudentId.value = courseId;
}

function addStudent() {
    window.location= `/students/create`
}

function editStudent() {
    window.location= `/students/${selectedStudentId.value}`;
}

function deleteStudent() {
    const student = props.students.data.find(ele => ele.id === selectedStudentId.value);

    if (student && student.courses_count <= 0) {
        if (confirm("Are you sure you want to delete this course?")) {
            Inertia.delete(route("students.destroy", selectedStudentId.value));
        }
    } else {
        alert("You cannot delete a staudent with enrolled courses");
    }
}

const isSelectedStudent = computed(() => {
    return !selectedStudentId.value;
});
</script>

<template>
    <Head title="Students" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Students: {{ students.total }}</h2>
        </template>

        <div class="flex justify-start mt-4 ml-4">
                <button 
                    class="bg-green-500 text-white py-2 px-4 rounded focus:outline-none focus:shadow-outline" 
                    type="button" 
                    @click="addStudent">Add
                </button>
                <button 
                    class="bg-blue-500 text-white ml-4 py-2 px-4 rounded focus:outline-none focus:shadow-outline" 
                    :class="{ 'bg-blue-200': isSelectedStudent }"
                    type="button" 
                    :disabled="isSelectedStudent"
                    @click="editStudent">Edit
                </button>
                <button 
                    class="bg-red-500 text-white ml-4 py-2 px-4 rounded focus:outline-none focus:shadow-outline" 
                    :class="{ 'bg-red-200': isSelectedStudent }"
                    type="button" 
                    :disabled="isSelectedStudent"
                    @click="deleteStudent">Delete
                </button>
        </div>

        <div class="flex flex-col items-center mt-6 ml-4 mr-4">
            <div class="w-full overflow-x-auto">
                <table class="table-auto border-collapse w-full px-4">
                    <thead>
                        <tr class="border-b border-gray-300">
                            <th class="px-4 py-2 text-left">First Name</th>
                            <th class="px-4 py-2 text-left">Last Name</th>
                            <th class="px-4 py-2 text-left">Age</th>
                            <th class="px-4 py-2 text-left">Email</th>
                            <th class="px-4 py-2 text-left">Courses</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="student in students.data" :key="student.id" @click="selectStudent(student.id)" @dblclick="editStudent()" :class="{ 'bg-blue-200': selectedStudentId === student.id }" class="border-b border-gray-300 cursor-pointer">
                            <td class="px-4 py-2 items-center">{{ student.first_name }}</td>
                            <td class="px-4 py-2 items-center">{{ student.last_name }}</td>
                            <td class="px-4 py-2 items-center">{{ student.age }}</td>
                            <td class="px-4 py-2 items-center">{{ student.email }}</td>
                            <td class="px-4 py-2 items-center">{{ student.courses_count }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="flex justify-end mt-4 ml-4">
                    <div v-if="students.prev_page_url" class="bg-blue-500 text-white ml-4 py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        <Link href='/students' as="button" :data="{ page: students.current_page -1 }">
                            Previous
                        </Link>
                    </div>
                    <div v-if="students.next_page_url" class="bg-blue-500 text-white ml-4 py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        <Link href='/students' as="button" :data="{ page: students.current_page +1 }">
                            Next
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
