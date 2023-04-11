<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import VueNumberInput from '@chenfengyuan/vue-number-input';
import { ref } from 'vue';
import { Modal } from 'usemodal-vue3';

const isModalVisible = ref(false);
const courseToEnroll = ref(null);

const props = defineProps({
    student: {
        type: Object,
        default: {
            first_name: '',
            last_name: '',
            age: 0,
            email: '',
        }
    },
    courses: {
        type: Array,
    },
});

function saveStudent() {    
    if(props.student.id) {
        const { id, first_name, last_name, age, email } = props.student;
        const data = {
            first_name, last_name, age, email,
        }
        Inertia.put(`/students/${id}`, data);
    } else {
        console.log(props.student);
        router.post('/students', props.student);
    }
}

function openEnrollModal() {
    isModalVisible.value = true;
}

function selectCourseToEnroll(courseId) {
    courseToEnroll.value = courseId;
}

function cancelEnroll() {
    courseToEnroll.value = null;
    isModalVisible.value = false;
}

function enrollStudent() {
    if (courseToEnroll.value) {
        Inertia.post(`/students/enroll/${props.student.id}`, { course_id: courseToEnroll.value});
    } else {
        alert('Please choose a course.');
    }
}

</script>

<template>
    <Head title="Students" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Student Detail</h2>
        </template>

        <div class="flex flex-row w-full gap-10 mt-4">
            <form class="w-full max-w-xl pl-4">
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="name">First Name</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="first_name" type="text" v-model="student.first_name" placeholder="Enter first name">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="name">Last Name</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="last_name" type="text" v-model="student.last_name" placeholder="Enter last name">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="name">Email</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="text" v-model="student.email" placeholder="Enter email">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="name">Age</label>
                    <vue-number-input v-model="student.age" :min="10" :max="100" inline class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></vue-number-input>
                </div>
                <div class="flex justify-left mt-11">
                    <button 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" 
                        type="button" 
                        @click="saveStudent">Save Student
                    </button>
                </div>
            </form>
            <div v-if="student.id" class="w-full max-w-xl pl-4">
                <h4 class="font-semibold text-md text-gray-800 leading-tight">Enrolled Courses: {{ student.courses_count }}</h4>
                <div class="overflow-y-scroll h-64">
                    <table class="table-auto border-collapse w-full px-4 mt-4">
                        <thead>
                            <tr class="border-b border-gray-300">
                                <th class="px-4 py-2 text-left">Course Name</th>
                                <th class="px-4 py-2 text-left">Start Date</th>
                                <th class="px-4 py-2 text-left">End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="course in student.courses" :key="course.id" class="border-b border-gray-300 hover:bg-gray-100">
                                <td class="px-4 py-2 items-center">{{ course.name }}</td>
                                <td class="px-4 py-2 items-center">{{ course.start_date }}</td>
                                <td class="px-4 py-2 items-center">{{ course.end_date }}</td>
                            </tr>
                        </tbody>
                    </table> 
                </div>
                <div class="flex justify-left mt-5">
                    <button 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" 
                        type="button" 
                        @click="openEnrollModal">Enroll
                    </button>
                </div>
            </div>
        </div>
        <Modal v-model:visible="isModalVisible" 
        title="Choose a course"
        with="md"
        :okButton="{
            onclick: enrollStudent,
            text: 'Enroll',
        }"
        :cancelButton="{
            onclick: cancelEnroll,
            text: 'Cancel'
        }"
        >
            <div class="overflow-y-scroll h-64">
                <table class="table-auto border-collapse w-full px-4 mt-2 ml-2 mr-2">
                    <thead>
                            <tr class="border-b border-gray-300">
                                <th class="px-4 py-2 text-left">Course Name</th>
                                <th class="px-4 py-2 text-left">Start Date</th>
                                <th class="px-4 py-2 text-left">End Date</th>
                            </tr>
                        </thead>
                    <tbody>
                            <tr v-for="course in courses" :key="course.id" @click="selectCourseToEnroll(course.id)" :class="{ 'bg-blue-200': courseToEnroll === course.id }" class="border-b border-gray-300">
                                <td class="px-4 py-2 items-center">{{ course.name }}</td>
                                <td class="px-4 py-2 items-center">{{ course.start_date }}</td>
                                <td class="px-4 py-2 items-center">{{ course.end_date }}</td>
                            </tr>
                    </tbody>
                </table>
            </div> 
        </Modal>
    </AuthenticatedLayout>
</template>



