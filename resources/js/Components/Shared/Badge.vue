<script setup>
import { computed } from 'vue';

const props = defineProps({
    hex: {
        type: String,
        required: true,
    },
});

const isLight = computed(() => {
    const hex = props.hex.replace('#', '');
    const r = parseInt(hex.substr(0, 2), 16);
    const g = parseInt(hex.substr(2, 2), 16);
    const b = parseInt(hex.substr(4, 2), 16);
    const brightness = (r * 299 + g * 587 + b * 114) / 1000;

    return brightness > 155;
});

const textColor = computed(() => {
    return isLight.value ? 'text-black' : 'text-white';
});
</script>

<template>
    <span
        class="text-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase"
        :style="`background-color: ${props.hex};`"
        :class="textColor"
    >
        <slot />
    </span>
</template>
