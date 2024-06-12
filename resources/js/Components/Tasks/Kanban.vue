<script setup>
import { ref } from 'vue';
import { useTaskStore } from '@/Utils/task';
import Column from '@/Components/Tasks/Column.vue';

const props = defineProps({
    parentId: {
        default: null
    }
});

const { statuses } = useTaskStore();

const dragging = ref(false);
</script>

<template>
    <div class="flex flex-col md:grid md:grid-cols-3 gap-5">
        <Suspense v-for="status in statuses" :key="status.id">
            <Column
                :label="status.name"
                :status-id="status.id"
                :parent-id="parentId"
                v-model:dragging="dragging"
            />
        </Suspense>
    </div>
</template>
