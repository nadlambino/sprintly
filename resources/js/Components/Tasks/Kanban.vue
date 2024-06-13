<script setup>
import { ref } from 'vue';
import { useKanbanColumnKeys } from '@/Utils/task';
import { useStatusStore } from '@/Utils/status';
import Column from '@/Components/Tasks/Column.vue';

const props = defineProps({
    parentId: {
        default: null
    }
});

const { statuses } = useStatusStore();
const [keys, update] = useKanbanColumnKeys();

const dragging = ref(false);
</script>

<template>
    <div class="flex flex-col md:grid md:grid-cols-3 gap-5">
        <Suspense v-for="status in statuses">
            <Column
                :key="keys[status.id]"
                :label="status.name"
                :status-id="status.id"
                :parent-id="parentId"
                v-model:dragging="dragging"
                @rerender="(previous, current) => update([previous, current])"
            />
        </Suspense>
    </div>
</template>
