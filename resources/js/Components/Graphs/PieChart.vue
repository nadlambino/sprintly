<script setup>
import { computed } from 'vue';

const props = defineProps({
    data: {
        type: Array,
        default: () => [],
    },
    total: {
        type: Number,
        default: () => 0
    },
    size: {
        type: Number,
        default: () => 300
    }
});

const calculatePath = (startAngle, endAngle) => {
    const radius = 100;
    const largeArcFlag = (endAngle - startAngle) > 180 ? 1 : 0;

    const startX = radius * Math.cos(Math.PI * startAngle / 180);
    const startY = radius * Math.sin(Math.PI * startAngle / 180);
    const endX = radius * Math.cos(Math.PI * endAngle / 180);
    const endY = radius * Math.sin(Math.PI * endAngle / 180);

    return `M 0 0 L ${startX} ${startY} A ${radius} ${radius} 0 ${largeArcFlag} 1 ${endX} ${endY} Z`;
};

const segmentPaths = computed(() => {
        let startAngle = 0;

        return props.data.map(segment => {
        const fraction = (segment.count / props.total);
        const endAngle = startAngle + fraction * 360;
        const path = calculatePath(startAngle, endAngle);
        const midAngle = startAngle + (fraction * 360) / 2;
        const textX = 70 * Math.cos(Math.PI * midAngle / 180);
        const textY = 70 * Math.sin(Math.PI * midAngle / 180);
        startAngle = endAngle;

        return { path, color: segment.color, textX, textY, count: segment.count, name: segment.name, percentage: Math.round(fraction * 100) };
    });
});
</script>

<template>
    <div class="flex gap-5 md:gap-10 items-center justify-center user-select-none">
        <svg :width="size" :height="size" viewBox="-100 -100 200 200">
            <g>
                <template v-for="(segment, index) in segmentPaths">
                    <path v-if="segment.count > 0"
                        :d="segment.path"
                        :fill="segment.color"
                        :key="index"
                        class="hover:opacity-90 transition duration-150 ease-in-out"
                    />
                    <text
                        v-if="segment.count > 0"
                        :x="segment.textX"
                        :y="segment.textY"
                        text-anchor="middle"
                        fill="white"
                        class="text-[10px] user-select-none"
                        :key="'text-' + index">
                        {{ segment.percentage }}%
                    </text>
                </template>
            </g>
        </svg>
        <div class="flex flex-col p-2.5">
            <template v-for="(segment, index) in segmentPaths" :key="'legend-' + index">
                <div v-if="segment.count > 0" class="flex items-center mb-1.5">
                    <span class="block w-5 h-5 mr-2.5" :style="{ backgroundColor: segment.color }"></span>
                    <span>{{ segment.name }}</span>
                </div>
            </template>
        </div>
    </div>
</template>

<style scoped>
.user-select-none {
    user-select: none;
}
</style>
