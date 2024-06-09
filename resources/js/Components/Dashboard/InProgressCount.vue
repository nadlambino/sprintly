<script setup>
import Counter from '@/Components/Counter.vue';
import { useTasks } from '@/Composables/useTasks';


const { total } = await useTasks({
    status: 'in progress',
    published: true,
    per_page: 1
});
</script>

<template>
    <Counter label="In Progress" :count="total || 0" />
</template>
