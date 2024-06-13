<script setup>
import { ref, watch, onBeforeMount } from 'vue';
import { useElementVisibility } from '@vueuse/core';
import { VueDraggableNext } from 'vue-draggable-next';
import { useTaskApi, useTaskStore } from '@/Utils/task';
import { useStatusStore } from '@/Utils/status';
import Task from '@/Components/Tasks/Task.vue';
import TaskSkeleton from '@/Components/Tasks/TaskSkeleton.vue';
import TaskEmpty from '@/Components/Tasks/TaskEmpty.vue';

const props = defineProps({
    label: {
        type: String,
        required: true,
    },
    statusId: {
        required: true,
    },
    parentId: {
        default: null
    }
});

const taskStore = useTaskStore();
const statusStore = useStatusStore();
const statusColor = statusStore.statuses.find(status => status.id === props.statusId)?.color;

const {
    data,
    total,
    isRequesting,
    isEmpty,
    hasNextPage,
    next,
    update,
    refetch
 } = await useTaskApi({
    status_id: props.statusId,
    published: true,
    search: taskStore.search,
    per_page: taskStore.perPage,
    parent_id: props.parentId,
});

onBeforeMount(refetch);

const target = ref(null);
const isVisible = useElementVisibility(target);
watch(isVisible, (visible) => visible && next());

const dragging = defineModel('dragging', { default: false});
const emits = defineEmits(['rerender']);

/**
 * Move task to its new status.
 *
 * @param {*} event
 */
const move = async (event) => {
    const id = event?.item?.getAttribute('data-id');
    const currentStatus = event?.item?.parentElement?.getAttribute('data-status');
    const previousStatus = event?.from?.getAttribute('data-status');

    if (!id || !currentStatus) {
        dragging.value = false;
        return;
    };

    update(id, { status_id: currentStatus })
        .then(() => emits('rerender', previousStatus, currentStatus))
        .finally(() => event?.item?.remove());
}
</script>

<template>
    <div class="flex flex-col gap-5 bg-white overflow-hidden shadow-sm rounded-lg max-h-screen">
        <div class="px-5 py-2 text-center font-bold bg-primary text-white" :style="{ backgroundColor: statusColor }">
            <p class="uppercase text-sm font-normal">{{ label }} ({{ total }})</p>
        </div>
        <TaskEmpty v-if="isEmpty && ! dragging" class="mx-5" />
        <div ref="container" class="flex flex-col gap-5 p-5 pt-0 overflow-y-auto h-auto">
            <div v-for="page in data?.pages" class="h-full">
                <VueDraggableNext
                    class="flex flex-col gap-5 relative"
                    :class="{ 'dragging': dragging }"
                    :data-status="statusId"
                    group='tasks'
                    @dragover="() => dragging = true"
                    @drop.prevent="() => dragging = false"
                    @add="move"
                >
                    <Task v-for="task in page" :key="task.id" :task="task" @destroy="refetch" />
                </VueDraggableNext>
            </div>
            <div v-if="hasNextPage" ref="target"></div>
            <p v-if="!hasNextPage && !isRequesting && !isEmpty" class="text-center text-muted">No more tasks</p>
            <TaskSkeleton v-if="isRequesting" />
        </div>
    </div>
</template>

<style scoped>
    .dragging {
        @apply opacity-50 border border-gray-400 border-dashed h-full min-h-[200px] overflow-hidden;
    }
</style>
