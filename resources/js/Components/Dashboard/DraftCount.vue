<script setup>
import Counter from '@/Components/Shared/Counter.vue';
import { useTaskApi } from '@/Utils/task';


const { total } = await useTaskApi({
    status: 'all',
    published: false,
    per_page: 1
});
</script>

<template>
    <Counter label="Drafts" :count="total || 0" />
</template>
