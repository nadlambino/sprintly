<script setup>
import TableColumn from '@/Components/Table/TableColumn.vue';

const props = defineProps({
    headers: Array,
    row: Object,
});

</script>

<template>
    <tr class="bg-white border-b hover:bg-gray-50">
        <TableColumn v-for="column in headers" :key="column.key" :column="column.key" :row="props.row" :class="column.class" />

        <td class="px-6 py-4 text-right">
            <slot name="actions" :row="props.row"></slot>
        </td>
    </tr>
</template>
