<script setup>
import Column from '@/Components/Tasks/Column.vue';
import { ref, reactive } from 'vue';

const dragging = ref(false);
const keys = reactive({
    todo: Math.random(),
    inprogress: Math.random(),
    done: Math.random()
})

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
                v-model:dragging="dragging"
                @rerender="rerender"
                :key="keys.todo"
            />
        </Suspense>
        <Suspense>
            <Column
                label="In Progress"
                status="in progress"
                v-model:dragging="dragging"
                @rerender="rerender"
                :key="keys.inprogress"
            />
        </Suspense>
        <Suspense>
            <Column
                label="Done"
                status="done"
                v-model:dragging="dragging"
                @rerender="rerender"
                :key="keys.done"
            />
        </Suspense>
    </div>
</template>
