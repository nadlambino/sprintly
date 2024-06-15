<script setup>
import { useSlots } from 'vue';
import { VueDraggableNext } from 'vue-draggable-next';
import TableRow from './TableRow.vue';

const props = defineProps({
    headers: Array,
    data: Array,
    isEmpty: Boolean,
    isRequesting: Boolean,
    hasNextPage: Boolean,
    total: Number,
    label: String,
    sortable: {
        type: Boolean,
        default: false
    },
    tableKey: {
        default: Math.random()
    }
});

const emit = defineEmits(['sort']);

const sort = (event) => {
    emit('sort', {
        id: event.item.getAttribute('data-id'),
        oldIndex: event.oldIndex,
        newIndex: event.newIndex
    });
}

const slots = useSlots();
</script>

<template>
    <div class="relative overflow-x-auto shadow-md rounded-lg max-h-[70vh]">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th v-for="header in headers" :key="header.key" scope="col" class="px-6 py-3">
                        {{ header.label }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <span class="sr-only">Action</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="isEmpty">
                    <td :colspan="headers.length + 1" class="text-center py-4">
                        No data available
                    </td>
                </tr>
                <VueDraggableNext v-if="sortable" :group="tableKey" style="display: contents; padding: 0" @end="sort">
                    <TableRow v-for="row in data" :key="row.id" :headers="headers" :row="row" class="cursor-grab" :data-id="row.id">
                        <template v-for="header in headers" :key="header.key" v-slot:[header.key]="{ data }">
                            <template v-if="slots[header.key]">
                                <slot :name="header.key" :data="data"></slot>
                            </template>
                            <template v-else>
                                {{ data }}
                            </template>
                        </template>
                        <template #actions="{ row }">
                            <slot name="actions" :row="row"></slot>
                        </template>
                    </TableRow>
                </VueDraggableNext>
                <template v-else>
                    <TableRow v-for="row in data" :key="row.id" :headers="headers" :row="row">
                        <template v-for="header in headers" :key="header.key" v-slot:[header.key]="{ data }">
                            <template v-if="slots[header.key]">
                                <slot :name="header.key" :data="data"></slot>
                            </template>
                            <template v-else>
                                {{ data }}
                            </template>
                        </template>
                        <template #actions="{ row }">
                            <slot name="actions" :row="row"></slot>
                        </template>
                    </TableRow>
                </template>
                <tr v-if="isRequesting">
                    <td :colspan="headers.length + 1" class="text-center py-4 bg-gray-50">
                        Loading...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
