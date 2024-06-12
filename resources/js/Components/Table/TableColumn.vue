<script setup>
import collect from 'collect.js';
import { computed } from 'vue';

const props = defineProps({
    column: String,
    row: Object,
    headers: Array,
});

const data = computed(() => {
    const dotted = props.column.split('.');

    return dotted.reduce((object, key) => object[key] ?? null, props.row);
});

const asColor = computed(() => collect(props.headers).firstWhere('key', props.column)?.as_color);
</script>

<template>
    <td class="px-6 py-4">
        <template v-if="asColor">
            <div class="w-6 h-6 rounded-full" :style="`background-color: ${data}`" :title="data"></div>
        </template>
        <template v-else>
            {{ data }}
        </template>
    </td>
</template>
