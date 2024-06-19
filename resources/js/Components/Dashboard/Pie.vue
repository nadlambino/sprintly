<script setup>
import { useTaskApi } from '@/Utils/task';
import { ref } from 'vue';
import PieChart from '@/Components/Graphs/PieChart.vue';

const { getMetrics } = useTaskApi();
const metrics = ref([]);
const total = ref(0);

await getMetrics().then(({ data }) => {
    total.value = data.data?.total || 0;
    metrics.value = data.data?.metrics || [];
});
</script>

<template>
    <PieChart :data="metrics" :total="total" />
</template>
