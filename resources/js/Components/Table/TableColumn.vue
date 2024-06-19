<script setup>
import { computed, useSlots } from 'vue';

const props = defineProps({
    column: String,
    row: Object,
    headers: Array,
});

const data = computed(() => {
    const dotted = props.column.split('.');

    if (dotted.length === 0) {
        return props.row[props.column];
    }

    return dotted.reduce((object, key) => object ? object[key] : null, props.row);
});

const slots = useSlots()
</script>

<template>
    <td class="px-6 py-4">
        <slot v-if="slots[props.column]" :name="column" :data="data" :row="row"></slot>
        <template v-else>{{ data }}</template>
    </td>
</template>
