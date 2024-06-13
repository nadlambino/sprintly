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

const sort = (event) => {
    sortStatus(event.item.getAttribute('data-id'), event.oldIndex, event.newIndex);
}
</script>

<template>
    <VueDraggableNext class="flex flex-col md:grid md:grid-cols-3 gap-5" group="kanban" @end="sort">
        <Suspense v-for="status in statuses">
            <Column
                :key="keys[status.id]"
                :label="status.name"
                :status-id="status.id"
                :parent-id="parentId"
                :data-id="status.id"
                v-model:dragging="dragging"
                @rerender="(previous, current) => update([previous, current])"
            />
        </Suspense>
    </VueDraggableNext>
</template>
