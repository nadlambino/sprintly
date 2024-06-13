<script setup>
import { ref } from 'vue';
import { useKanbanColumnKeys } from '@/Utils/task';
import { useStatusApi, useStatusSort, useStatusStore } from '@/Utils/status';
import { VueDraggableNext } from 'vue-draggable-next';
import Column from '@/Components/Tasks/Column.vue';

const props = defineProps({
    parentId: {
        default: null
    }
});

const { statuses } = useStatusStore();
const sortStatus = useStatusSort();
const [keys, update] = useKanbanColumnKeys();

const dragging = ref(false);

const statusDragging = ref(false);
const sort = (event) => {
    sortStatus(event.item.getAttribute('data-id'), event.oldIndex, event.newIndex);
    statusDragging.value = false;
}
</script>

<template>
    <VueDraggableNext class="flex flex-col md:grid md:grid-cols-3 gap-5" group="kanban" @end="sort" :class="{ 'status-dragging': statusDragging }">
        <Suspense v-for="status in statuses">
            <Column
                class="cursor-grab"
                :key="keys[status.id]"
                :label="status.name"
                :status-id="status.id"
                :parent-id="parentId"
                :data-id="status.id"
                @dragstart="statusDragging = true"
                @dragend="statusDragging = false"
                v-model:dragging="dragging"
                @rerender="(previous, current) => update([previous, current])"
            />
        </Suspense>
    </VueDraggableNext>
</template>

<style scoped>
.status-dragging {
    @apply opacity-70 border border-gray-400 border-dashed h-full min-h-[200px] overflow-hidden;
}
</style>
