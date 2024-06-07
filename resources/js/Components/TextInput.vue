<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({
    type: {
        type: String,
        default: 'text',
    }
})

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <textarea
        v-if="type === 'textarea'"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        rows="5"
        v-model="model"
        ref="input"
    />
    <input
        v-else
        :type="type"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        v-model="model"
        ref="input"
    />
</template>
