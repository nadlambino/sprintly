<script setup>
import { ref, reactive } from 'vue';
import Column from '@/Components/Tasks/Column.vue';

const props = defineProps({
    parentId: {
        default: null
    }
});

const dragging = ref(false);
const key = ref(Math.random());

/**
 * Forces a rerender by changing the column keys.
 *
 * @param {string} status
 * @param {string} previousStatus
 */
const rerender = () => {
    key.value = Math.random();
}
</script>

<template>
    <div class="flex flex-col md:grid md:grid-cols-3 gap-5">
        <Suspense>
            <Column
                label="To Do"
                status="todo"
                accent-color="primary"
                v-model:dragging="dragging"
                @rerender="rerender"
                :key="key"
                :parent-id="parentId"
            />
        </Suspense>
        <Suspense>
            <Column
                label="In Progress"
                status="in progress"
                accent-color="yellow-500"
                v-model:dragging="dragging"
                @rerender="rerender"
                :key="key"
                :parent-id="parentId"
            />
        </Suspense>
        <Suspense>
            <Column
                label="Done"
                status="done"
                accent-color="green-500"
                v-model:dragging="dragging"
                @rerender="rerender"
                :key="key"
                :parent-id="parentId"
            />
        </Suspense>
    </div>
</template>
