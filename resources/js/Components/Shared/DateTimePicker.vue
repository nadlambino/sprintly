<script setup>
const model = defineModel();
</script>

<template>
    <input
        type="datetime-local"
        class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm"
        v-model="model"
        ref="input"
    />
</template>
