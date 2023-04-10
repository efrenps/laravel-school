<script setup>
const props = defineProps({
  course: {
    type: Object,
    required: true,
  },
});
</script>

<template>
    <div class="bg-white rounded-lg shadow-lg">
      <div
        class="h-48 bg-gray-300 rounded-t-lg"
        :style="{ backgroundImage: 'url(https://fastly.picsum.photos/id/0/5000/3333.jpg?hmac=_j6ghY5fCfSD6tvtcV74zXivkJSPIfR9B8w34XeQmvU)',
                 backgroundSize: 'cover' }"
      ></div>
      <div class="p-6">
        <h3 class="text-xl font-semibold mb-2">{{ course.name }}</h3>
        <div class="flex justify-between items-center">
          <span class="text-gray-600"></span>
          <div class="px-4 py-2 bg-indigo-500 text-white rounded-full">{{ course.students_count }} estudiantes</div>
        </div>
      </div>
    </div>
</template>