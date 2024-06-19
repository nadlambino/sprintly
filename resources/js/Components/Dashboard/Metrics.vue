<script setup>
import Metric from '@/Components/Graphs/Metric.vue';

const props = defineProps({
    total: {
        type: Number,
        default: 0
    },
    metrics: {
        type: Array,
        default: []
    }
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
