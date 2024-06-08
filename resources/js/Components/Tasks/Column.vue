<script setup>
import { useTasks } from '@/Composables/useTasks';
import { computed } from 'vue';
import { useTasksStore } from '@/Stores/useTasksStore';
import { useElementVisibility } from '@vueuse/core'
import { ref, watch } from 'vue';
import Task from './Task.vue';
import TaskSkeleton from './TaskSkeleton.vue';
import TaskEmpty from './TaskEmpty.vue';

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

const { data, isPending, isFetchingNextPage, refetch, fetchNextPage, page } = await useTasks({
    status: props.status,
    published: true,
    search: tasksStore.search,
    per_page: tasksStore.perPage
});

const paginationIdentifier = ref(null);
const isPaginationIdentifierVisibleInViewPort = useElementVisibility(paginationIdentifier);

const getNextPage = async (visible) => {
    if (visible === false) return;

    fetchNextPage();
}

watch(isPaginationIdentifierVisibleInViewPort, getNextPage);
</script>

<template>
    <div class="flex flex-col gap-5 bg-white overflow-hidden shadow-sm sm:rounded-lg h-screen">
        <div class="px-5 py-2 text-center font-bold bg-muted text-white">
            <h1 class="">{{ label }}</h1>
        </div>
        <TaskEmpty class="mx-5" v-if="!isPending && data?.pages[page ?? 0]?.length === 0" />
        <div ref="container" class="flex flex-col gap-5 p-5 pt-0 overflow-y-auto h-full">
            <div v-for="page in data?.pages" class="flex flex-col gap-5">
                <Task v-for="task in page" :key="task.id" :task="task" @destroy="refetch" />
            </div>
            <TaskSkeleton v-if="isPending || isFetchingNextPage" />
            <div ref="paginationIdentifier"></div>
        </div>
    </div>
</template>
@/Stores/useTasksStore
