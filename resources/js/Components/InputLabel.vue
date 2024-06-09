<script setup>
defineProps({
    value: {
        type: String,
    },
    required: {
        type: Boolean,
        default: false
    }
});
</script>

<template>
    <label class="font-medium text-gray-700">
        <span v-if="value"><span v-html="value"></span><span class="text-red-600 font-bold" v-if="required">*</span></span>
        <span v-else><slot /></span>
    </label>
</template>
