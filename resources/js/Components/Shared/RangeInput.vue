<script setup>
const model = defineModel();
</script>

<template>
    <input v-model="model" type="range" class="range-input accent-primary" />
</template>

<style scoped>
.range-input::-webkit-slider-thumb {
    @apply bg-primary rounded-full cursor-pointer -top-1 relative;
}

.range-input::-moz-range-thumb {
    @apply bg-primary rounded-full cursor-pointer -top-1 relative;
}

.range-input::-webkit-slider-runnable-track {
    @apply bg-gray-200 h-2 rounded-full;
}

.range-input::-moz-range-track {
    @apply bg-gray-200 h-2 rounded-full;
}
</style>
