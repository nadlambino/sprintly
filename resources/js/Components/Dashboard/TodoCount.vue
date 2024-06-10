<script setup>
import Counter from '@/Components/Counter.vue';
import { useTaskApi } from '@/Utils/task';


const { total } = await useTaskApi({
    status: 'todo',
    published: true,
    per_page: 1
});
</script>

<template>
    <Counter label="Todos" :count="total || 0" />
</template>
