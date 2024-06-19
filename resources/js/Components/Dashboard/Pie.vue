<script setup>
import PieChart from '@/Components/Graphs/PieChart.vue';

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
    <PieChart :data="metrics" :total="total" />
</template>
