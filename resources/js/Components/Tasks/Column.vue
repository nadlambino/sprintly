<script setup>
import { useTasks } from '@/Composables/useTasks';
import Task from './Task.vue';
import TaskSkeleton from './TaskSkeleton.vue';
import TaskEmpty from './TaskEmpty.vue';
import { computed } from 'vue';
import { useTasksStore } from '@/Stores/useTasksStore';

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

const tasksStore = useTasksStore();
const sort = defineModel('sort');

const { data: tasks, isPending, refetch } = await useTasks({
    status: props.status,
    sort: sort.value,
    draft: false,
    search: tasksStore.search,
    page: 1,
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
        <div class="flex flex-col gap-5 p-5 pt-0 overflow-y-auto h-full">
            <Task v-for="task in tasks" :key="task.id" :task="task" @destroy="refetch" />
            <TaskSkeleton v-if="isPending" />
            <TaskEmpty v-if="!isPending && tasks?.length === 0" />
        </div>
    </div>
</template>
@/Stores/useTasksStore
