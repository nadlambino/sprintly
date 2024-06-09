<script setup>
import { useTasks } from '@/Composables/useTasks';
import { useTasksStore } from '@/Stores/useTasksStore';
import { useElementVisibility } from '@vueuse/core'
import { ref, watch } from 'vue';
import { VueDraggableNext } from 'vue-draggable-next';
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

const dragging = defineModel('dragging', { default: false});
const emits = defineEmits(['rerender']);

const move = async (event) => {
    const id = event?.item?.getAttribute('data-id');
    const status = event?.item?.parentElement?.getAttribute('data-status');
    const previousStatus = event?.item?.getAttribute('data-status');

    await window.axios.put(route('api.tasks.update', { task: id }), { status })
        .then(() => {
            emits('rerender', status?.replace(' ', ''), previousStatus?.replace(' ', ''));
        })
        .finally(() => event?.item?.remove());
}
</script>

<template>
    <div class="flex flex-col gap-5 bg-white overflow-hidden shadow-sm rounded-lg max-h-screen">
        <div class="px-5 py-2 text-center font-bold bg-muted text-white">
            <p class="uppercase text-sm font-normal">{{ label }} ({{ total }})</p>
        </div>
        <TaskEmpty class="mx-5" v-if="isEmpty && ! dragging" :status="status" />
        <div ref="container" class="flex flex-col gap-5 p-5 pt-0 overflow-y-auto h-full">
            <div v-for="page in data?.pages">
                <VueDraggableNext
                    class="flex flex-col gap-5 relative"
                    :class="{ 'dragging': dragging }"
                    @dragover="() => dragging = true"
                    @drop.prevent="() => dragging = false"
                    :data-status="status"
                    group='tasks'
                    @add="move"
                >
                    <Task v-for="task in page" :key="task.id" :task="task" @destroy="refetch" />
                </VueDraggableNext>
            </div>
            <div v-if="hasNextPage" ref="target"></div>
            <p v-if="!hasNextPage && !isRequesting && !isEmpty" class="text-center text-muted">No more tasks</p>
        </div>
        <TaskSkeleton class="mx-5" v-if="isRequesting" />
    </div>
</template>

<style scoped>
    .dragging {
        @apply opacity-50 border border-gray-400 border-dashed h-screen;
    }
</style>
