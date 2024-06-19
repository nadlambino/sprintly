<script setup>
import { useTaskApi } from '@/Utils/task';
import { ref } from 'vue';
import Metric from '@/Components/Graphs/Metric.vue';

const { getMetrics } = useTaskApi();
const metrics = ref([]);
const total = ref(0);

await getMetrics().then(({ data }) => {
    total.value = data.data?.total || 0;
    metrics.value = data.data?.metrics || [];
});
</script>

<template>
    <div class="grid grid-cols-3 gap-5">
        <Metric
            v-for="(metric, index) in metrics"
            :key="index"
            :label="metric.name"
            :count="metric.count"
            :total="total"
            :size="120"
            :width="15"
            :color="metric.color"
        />
    </div>
</template>
