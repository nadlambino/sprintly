<script setup>
import { computed } from 'vue';

const props = defineProps({
    count: {
        type: Number,
        required: true
    },
    total: {
        type: Number,
        required: true
    },
    label: {
        type: String,
        required: true
    },
    size: {
        type: Number,
        default: () => 200
    },
    color: {
        type: String,
        default: () => '#007bff'
    },
    width: {
        type: Number,
        default: () => 25
    },
    progressClass: {
        type: String,
        default: () => 'bg-primary'
    },
    backgroundClass: {
        type: String,
        default: () => 'bg-gray-200'
    },
    textClass: {
        type: String,
        default: () => 'text-primary'
    }
});

const percentage = computed(() => Math.round(props.count / props.total * 100) || 0);
</script>

<template>
    <div class="metric-container">
        <svg :width="size" :height="size" :viewBox="`0 0 ${size} ${size}`" class="circular-progress" :style="`--progress: ${percentage}`">
            <circle class="bg" :class="backgroundClass"></circle>
            <circle class="fg" :class="progressClass"></circle>
            <text :x="size / 2" :y="size / 2" dominant-baseline="middle" text-anchor="middle" class="progress-text" :class="textClass">{{ percentage }}%</text>
        </svg>
        <div>
            <h1 class="text-lg font-semibold text-gray-600 uppercase">{{ label }}</h1>
            <p class="text-gray-600">{{ count }} of {{ total }}</p>
        </div>
    </div>
</template>

<style scoped>
.metric-container {
    @apply p-5 shadow-md bg-white rounded flex items-center cursor-default gap-5;

    user-select: none;
    border-top: 5px solid;
    border-top-color: v-bind(color);
}

.circular-progress {
    --size: v-bind(`${size}px`);
    --half-size: calc(var(--size) / 2);
    --stroke-width: v-bind(`${width}px`);
    --radius: calc((var(--size) - var(--stroke-width)) / 2);
    --circumference: calc(var(--radius) * pi * 2);
    --dash: calc((var(--progress) * var(--circumference)) / 100);
}

.circular-progress circle {
    cx: var(--half-size);
    cy: var(--half-size);
    r: var(--radius);
    stroke-width: var(--stroke-width);
    fill: none;
}

.circular-progress circle.bg {
    @apply stroke-gray-200;
}

.circular-progress circle.fg {
    @apply stroke-primary;

    transform: rotate(-90deg);
    transform-origin: var(--half-size) var(--half-size);
    stroke-dasharray: var(--dash) calc(var(--circumference) - var(--dash));
    transition: stroke-dasharray 0.3s linear 0s;
    stroke: v-bind(color);
}

.progress-text {
    user-select: none;
    font-size: calc(var(--half-size) / 2);
    font-weight: bold;
    transition: fill 0.4s linear 0.4s;
    fill: v-bind(color);
}
</style>
