<script setup>
import { usePriorityLevelApi } from '@/Utils/priority-level';
import { Link } from '@inertiajs/vue3';
import SimpleTable from '@/Components/Table/SimpleTable.vue';
import ConfirmButton from '@/Components/Shared/ConfirmButton.vue';

const props = defineProps({
    headers: {
        type: Array,
        default: () => ([
            { key: 'name', label: 'Name' },
            { key: 'description', label: 'Description' },
            { key: 'color', label: 'Color' },
            { key: 'score', label: 'Score' },
            { key: 'created_at', label: 'Created At' },
            { key: 'updated_at', label: 'Updated At'}
        ])
    },
    editable: {
        type: Boolean,
        default: false
    },
    deletable: {
        type: Boolean,
        default: false
    },
    tableKey: {
        default: Math.random()
    }
})

const { data, isRequesting, isEmpty, destroy, refetch } = usePriorityLevelApi();

</script>

<template>
    <SimpleTable :is-empty="isEmpty" :is-requesting="isRequesting" :data="data" :headers="headers" label="priority levels" :total="data?.length || 0" >
        <template #color="{ data }">
            <div class="w-6 h-6 rounded-full" :style="`background-color: ${data}`" :title="data"></div>
        </template>
        <template #actions="{ row }">
            <div class="flex gap-2">
                <Link v-if="editable" :href="route('priority-levels.edit', row.id)" class="bg-primary hover:bg-primary/80 text-white uppercase text-xs font-bold py-1 px-2 rounded">Edit</Link>
                <ConfirmButton
                    v-if="deletable"
                    message="Are you sure you want to delete this?"
                    @confirm="() => destroy(row.id).then(refetch)"
                    class="bg-red-500 hover:bg-red-400 text-white uppercase text-xs font-bold py-1 px-2 rounded">
                    Delete
                </ConfirmButton>
            </div>
        </template>
    </SimpleTable>
</template>
