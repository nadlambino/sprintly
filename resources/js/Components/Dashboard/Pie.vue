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
    <div class="w-full bg-white p-5 shadow-lg">
        <PieChart :data="metrics" :total="total" />
    </div>
</template>
