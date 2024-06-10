<script setup>
import Counter from '@/Components/Counter.vue';
import { useTaskApi } from '@/Utils/task';


const { total } = await useTaskApi({
    status: 'in progress',
    published: true,
    per_page: 1
});
</script>

<template>
    <Counter label="In Progress" :count="total || 0" />
</template>
