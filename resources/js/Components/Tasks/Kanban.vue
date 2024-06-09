<script setup>
import Column from '@/Components/Tasks/Column.vue';
</script>

<template>
    <div class="flex flex-col md:grid md:grid-cols-3 gap-5">
        <Suspense>
            <Column label="To Do" status="todo" />
        </Suspense>
        <Suspense>
            <Column label="In Progress" status="in progress" />
        </Suspense>
        <Suspense>
            <Column label="Done" status="done" />
        </Suspense>
    </div>
</template>
