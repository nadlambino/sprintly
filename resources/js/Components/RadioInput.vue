<script setup>
const props = defineProps({
    value: String|Number
})

const model = defineModel({
    type: Number,
    required: true,
});
</script>

<template>
    <input
        type="radio"
        class="border-gray-400 focus:border-primary/80 focus:ring-primary rounded-full shadow-sm"
        v-model="model"
        :value="value"
        :id="value"
    />
</template>
