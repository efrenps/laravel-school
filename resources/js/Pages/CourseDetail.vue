<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import { ElSelect, ElOption } from 'element-plus';
import 'element-plus/dist/index.css';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    course: {
        type: Object,
        default: {
            name: '',
            students_count: 0,
            start_date: null,
            end_date: null
        }
    },
    schedules: {
        type: Array,
    },
});

const config = ref({
        wrap: true, // set wrap to true only when using 'input-group'
        altFormat: 'M j, Y',
        altInput: true,
        dateFormat: 'Y-m-d',
});


function onChange(selectedDates, dateStr, instance) {
    console.log('Date change hook was called', dateStr);
}

function saveCourse() {    
    if(props.course.id) {
        const { id, name, start_date, end_date, schedule_id } = props.course;
        const data = {
            name, start_date, end_date, schedule_id,
        }
        Inertia.put(`/courses/${id}`, data);
    } else {
        router.post('/courses', props.course);
    }
}

function onChangeSelect(value) {
    selectedSchedule.value = value;
    props.course.schedule_id = value;
}

const selectedSchedule = ref(props.course ? props.course.schedule_id : null);

</script>

<template>
    <Head title="Courses" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Course Detail</h2>
        </template>

        <div class="flex flex-row w-full gap-10 mt-4">
            <form class="w-full max-w-xl pl-4">
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="name">Course Name</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" v-model="course.name" placeholder="Enter course name">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="start-date">Schedule</label>
                    <el-select v-model="selectedSchedule" placeholder="Select" @change="onChangeSelect" class="w-full">
                        <el-option
                            v-for="schedule in schedules"
                            :key="schedule.id"
                            :value="schedule.id"
                            :label="schedule.name"
                        ></el-option>
                    </el-select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="start-date">Start Date</label>
                    <flat-pickr
                        v-model="course.start_date"
                        :config="config"
                        @on-change="onChange"
                        class="form-control"
                        placeholder="Select date"
                        name="start_date"/>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2" for="end-date">End Date</label>
                    <flat-pickr
                        v-model="course.end_date"
                        :config="config"
                        @on-change="onChange"
                        class="form-control"
                        placeholder="Select date"
                        name="end_date"/>
                </div>
                <div class="flex justify-left mt-11">
                    <button 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" 
                        type="button" 
                        @click="saveCourse">Save Course
                    </button>
                </div>
            </form>
            <div v-if="course.id" class="w-full max-w-xl pl-4">
                <h4 class="font-semibold text-md text-gray-800 leading-tight">Enrolled Students: {{ course.students_count }}</h4>
                <div class="overflow-y-scroll h-64">
                    <table class="table-auto border-collapse w-full px-4 mt-4">
                        <thead>
                            <tr class="border-b border-gray-300">
                                <th class="px-4 py-2 text-left">First Name</th>
                                <th class="px-4 py-2 text-left">Last Name</th>
                                <th class="px-4 py-2 text-left">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="student in course.students" :key="student.id" class="border-b border-gray-300 hover:bg-gray-100">
                                <td class="px-4 py-2 items-center">{{ student.first_name }}</td>
                                <td class="px-4 py-2 items-center">{{ student.last_name }}</td>
                                <td class="px-4 py-2 items-center">{{ student.email }}</td>
                            </tr>
                        </tbody>
                    </table> 
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>



