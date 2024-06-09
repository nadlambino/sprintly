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

const totalShownData = computed(() => props.data?.reduce((total, page) => total + page.length, 0));
</script>

<template>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg max-h-[70vh]">
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
                <template v-for="(page, index) in data" :key="index">
                    <TableRow v-for="row in page" :key="row.id" :headers="headers" :row="row" >
                        <template #actions="{ row }">
                            <slot name="actions" :row="row"></slot>
                        </template>
                    </TableRow>
                </template>
                <tr v-if="hasNextPage" ref="target"></tr>
                <tr v-if="isRequesting">
                    <td :colspan="headers.length + 1" class="text-center py-4">
                        Loading...
                    </td>
                </tr>
                <tr v-if="!hasNextPage && !isRequesting && !isEmpty" class="text-center text-muted py-2">
                    <td :colspan="headers.length + 1" class="py-4">
                        No more data
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="flex justify-end items-center gap-3 mt-2">
        <small class="text-muted">Showing {{ totalShownData }} of {{ total }} {{ label }}</small>
    </div>
</template>
