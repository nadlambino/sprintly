<script setup>
import { useStatusApi } from '@/Utils/status';
import { Link } from '@inertiajs/vue3';
import SimpleTable from '@/Components/Table/SimpleTable.vue';

const props = defineProps({
    headers: {
        type: Array,
        default: () => ([
            { key: 'name', label: 'Name' },
            { key: 'description', label: 'Description' },
            { key: 'color', label: 'Color', as_color: true },
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
    }
})

const { data, update, destroy, restore, refetch } = useStatusApi({
    trashed: props.trashed
});

</script>

<template>
    <SimpleTable :data="data" :headers="headers" label="statuses" :total="data?.length || 0">
        <template #actions="{ row }">
            <div class="flex gap-2">
                <Link v-if="editable" :href="route('statuses.edit', row.id)" class="bg-primary hover:bg-primary/80 text-white uppercase text-xs font-bold py-1 px-2 rounded">Edit</Link>
                <button v-if="deletable" @click="() => destroy(row.id).then(refetch)" class="bg-red-500 hover:bg-red-400 text-white uppercase text-xs font-bold py-1 px-2 rounded">Delete</button>
                <button v-if="restorable" @click="() => restore(row.id).then(refetch)" class="bg-green-500 hover:bg-green-400 text-white uppercase text-xs font-bold py-1 px-2 rounded">Restore</button>
            </div>
        </template>
    </SimpleTable>
</template>