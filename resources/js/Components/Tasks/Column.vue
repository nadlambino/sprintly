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
    search: search.value
});

const headBgColor = computed(() => {
    return props.status === 'todo' ? 'bg-blue-400' : props.status === 'in progress' ? 'bg-yellow-300' : 'bg-green-300';
});
</script>

<template>
    <div class="flex flex-col gap-5 bg-white overflow-hidden shadow-sm sm:rounded-lg h-screen">
        <div :class="headBgColor" class="px-5 py-2 text-center font-bold">
            <h1 class="">{{ label }}</h1>
        </div>
        <div class="flex flex-col gap-5 p-5 pt-0">
            <Task v-for="task in data" :key="task.id" :task="task" />
        </div>
    </div>
</template>
