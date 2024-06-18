<script setup>
const props = defineProps({
    count: Number,
    label: String,
});
</script>

<template>
    <div class="bg-white flex flex-col justify-center items-center gap-3 p-5 overflow-hidden shadow-sm rounded-lg w-full md:w-1/2 lg:w-1/3">
        <h1 class="text-5xl font-bold text-gray-700">{{ count }}</h1>
        <p class="text-gray-700 uppercase">{{ label }}</p>
    </div>
</template>
