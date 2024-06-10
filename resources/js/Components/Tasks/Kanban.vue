<script setup>
import { ref, reactive } from 'vue';
import Column from '@/Components/Tasks/Column.vue';

const dragging = ref(false);
const keys = reactive({
    todo: Math.random(),
    inprogress: Math.random(),
    done: Math.random()
})

/**
 * Forces a rerender by changing the column keys.
 *
 * @param {string} status
 * @param {string} previousStatus
 */
const rerender = (status, previousStatus) => {
    keys[status] = Math.random();
    keys[previousStatus] = Math.random();
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
                :key="keys.todo"
            />
        </Suspense>
        <Suspense>
            <Column
                label="In Progress"
                status="in progress"
                accent-color="yellow-500"
                v-model:dragging="dragging"
                @rerender="rerender"
                :key="keys.inprogress"
            />
        </Suspense>
        <Suspense>
            <Column
                label="Done"
                status="done"
                accent-color="green-500"
                v-model:dragging="dragging"
                @rerender="rerender"
                :key="keys.done"
            />
        </Suspense>
    </div>
</template>
