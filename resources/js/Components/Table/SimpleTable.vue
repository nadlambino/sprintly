<script setup>
import { ref, watch, computed } from 'vue';
import { useElementVisibility } from '@vueuse/core';
import TableRow from './TableRow.vue';

const props = defineProps({
    headers: Array,
    data: Array,
    isEmpty: Boolean,
    isRequesting: Boolean,
    hasNextPage: Boolean,
    total: Number,
    label: String
});

const emit = defineEmits(['next']);

const target = ref(null);
const isVisible = useElementVisibility(target);
watch(isVisible, (visible) => visible && emit('next'));
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
                <TableRow v-for="row in data" :key="row.id" :headers="headers" :row="row" >
                    <template #actions="{ row }">
                        <slot name="actions" :row="row"></slot>
                    </template>
                </TableRow>
                <tr v-if="hasNextPage" ref="target"></tr>
                <tr v-if="isRequesting">
                    <td :colspan="headers.length + 1" class="text-center py-4 bg-gray-50">
                        Loading...
                    </td>
                </tr>
                <tr v-if="!hasNextPage && !isRequesting && !isEmpty" class="text-center text-muted py-2">
                    <td :colspan="headers.length + 1" class="py-4 bg-gray-50">
                        No more data
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>