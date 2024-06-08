<script setup>
const props = defineProps({
    value: String|Number
})

const model = defineModel({
    required: true,
});
</script>

<template>
    <input
        type="radio"
        class="w-4 h-4 text-primary bg-gray-100 border-gray-300 focus:ring-primary/60 focus:ring-2"
        v-model="model"
        :value="value"
        :id="value"
    />
</template>
