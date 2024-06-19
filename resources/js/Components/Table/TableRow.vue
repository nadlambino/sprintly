<script setup>
import TableColumn from '@/Components/Table/TableColumn.vue';

const props = defineProps({
    headers: Array,
    row: Object,
});
</script>

<template>
    <tr class="bg-white border-b hover:bg-gray-50">
        <TableColumn v-for="column in headers" :key="column.key" :column="column.key" :row="row" :headers="headers" :class="column.class">
            <template v-slot:[column.key]="{ data, row }">
                <slot :name="column.key" :data="data" :row="row"></slot>
            </template>
        </TableColumn>

        <td class="px-6 py-4 text-right">
            <slot name="actions" :row="row"></slot>
        </td>
    </tr>
</template>
