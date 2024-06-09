<script setup>
import { useTasks } from '@/Composables/useTasks';
import { useTasksStore } from '@/Stores/useTasksStore';
import { useElementVisibility } from '@vueuse/core'
import { ref, watch } from 'vue';
import Task from '@/Components/Tasks/Task.vue';
import TaskSkeleton from '@/Components/Tasks/TaskSkeleton.vue';
import TaskEmpty from '@/Components/Tasks/TaskEmpty.vue';

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

const { data, total, isRequesting, isEmpty, hasNextPage, refetch, next } = await useTasks({
    status: props.status,
    published: true,
    search: tasksStore.search,
    per_page: tasksStore.perPage
});

const target = ref(null);
const isVisible = useElementVisibility(target);

watch(isVisible, (visible) => visible && next());
</script>

<template>
    <div class="flex flex-col gap-5 bg-white overflow-hidden shadow-sm sm:rounded-lg h-screen">
        <div class="px-5 py-2 text-center font-bold bg-muted text-white">
            <p class="uppercase text-sm font-normal">{{ label }} ({{ total }})</p>
        </div>
        <TaskEmpty class="mx-5" v-if="isEmpty" :status="status" />
        <div ref="container" class="flex flex-col gap-5 p-5 pt-0 overflow-y-auto h-full">
            <div v-for="page in data?.pages" class="flex flex-col gap-5">
                <Task v-for="task in page" :key="task.id" :task="task" @destroy="refetch" />
            </div>
            <TaskSkeleton v-if="isRequesting" />
            <div v-if="hasNextPage" ref="target"></div>
            <p v-if="!hasNextPage && !isRequesting && !isEmpty" class="text-center text-muted">No more tasks</p>
        </div>
    </div>
</template>
