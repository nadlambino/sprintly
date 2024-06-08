<script setup>
const props = defineProps({
    label: String
});

const model = defineModel();
</script>

<template>
    <label class="inline-flex items-center cursor-pointer">
        <input :checked="model" type="checkbox" v-model="model" class="sr-only peer">
        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/60 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
        <span class="ms-3 text-sm text-gray-900">{{ label }}</span>
    </label>
</template>
