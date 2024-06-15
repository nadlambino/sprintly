<script setup>
import { useStatusApi, useStatusSort } from '@/Utils/status';
import { Link } from '@inertiajs/vue3';
import SimpleTable from '@/Components/Table/SimpleTable.vue';

const props = defineProps({
    headers: {
        type: Array,
        default: () => ([
            { key: 'order', label: 'Order' },
            { key: 'name', label: 'Name' },
            { key: 'description', label: 'Description' },
            { key: 'color', label: 'Color' },
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
    restorable: {
        type: Boolean,
        default: false
    },
    trashed: {
        type: Boolean,
        default: false
    },
    sortable: {
        type: Boolean,
        default: false
    },
    tableKey: {
        default: Math.random()
    }
})

const { data, isRequesting, destroy, restore, refetch } = useStatusApi({
    trashed: props.trashed
});

const sort = useStatusSort();

const updateOrder = async ({ id, oldIndex, newIndex}) => {
    sort(id, oldIndex, newIndex).then(refetch);
}

</script>

<template>
    <SimpleTable :is-requesting="isRequesting" :data="data" :headers="headers" label="statuses" :total="data?.length || 0" :sortable="sortable" :table-key="tableKey" @sort="updateOrder">
        <template #color="{ data }">
            <div class="w-6 h-6 rounded-full" :style="`background-color: ${data}`" :title="data"></div>
        </template>
        <template #actions="{ row }">
            <div class="flex gap-2">
                <p v-if="row.is_default" class="bg-gray-400 text-white uppercase text-xs font-bold py-1 px-2 rounded">Default</p>
                <template v-else>
                    <Link v-if="editable" :href="route('statuses.edit', row.id)" class="bg-primary hover:bg-primary/80 text-white uppercase text-xs font-bold py-1 px-2 rounded">Edit</Link>
                    <button v-if="deletable" @click="() => destroy(row.id).then(refetch)" class="bg-red-500 hover:bg-red-400 text-white uppercase text-xs font-bold py-1 px-2 rounded">Delete</button>
                    <button v-if="restorable" @click="() => restore(row.id).then(refetch)" class="bg-green-500 hover:bg-green-400 text-white uppercase text-xs font-bold py-1 px-2 rounded">Restore</button>
                </template>
            </div>
        </template>
    </SimpleTable>
</template>
