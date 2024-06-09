<script setup>
import { computed } from 'vue';

const props = defineProps({
    column: String,
    row: Object,
});

const data = computed(() => {
    const dotted = props.column.split('.');

    return dotted.reduce((object, key) => object[key] ?? null, props.row);
})
</script>

<template>
    <td class="px-6 py-4">
        {{ data }}
    </td>
</template>
