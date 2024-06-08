<script setup>
import { useTasks } from '@/Composables/useTasks';
import Task from './Task.vue';
import { computed } from 'vue';

const props = defineProps({
    label: {
        type: String,
        required: true,
    },
    status: {
        type: String,
        required: true,
    }
});

const search = defineModel('search')
const sort = defineModel('sort');

const { data } = await useTasks({
    status: props.status,
    sort: sort.value,
    draft: false,
    search: search.value,
    page: 2,
    per_page: 5
});

const headBgColor = computed(() => {
    return props.status === 'todo' ? 'bg-primary text-white' : props.status === 'in progress' ? 'bg-yellow-300' : 'bg-green-300';
});
</script>

<template>
    <div class="flex flex-col gap-5 bg-white overflow-hidden shadow-sm sm:rounded-lg h-screen">
        <div :class="headBgColor" class="px-5 py-2 text-center font-bold">
            <h1 class="">{{ label }}</h1>
        </div>
        <div class="flex flex-col gap-5 p-5 pt-0 overflow-y-scroll h-full">
            <Task v-for="task in data" :key="task.id" :task="task" />
        </div>
    </div>
</template>
