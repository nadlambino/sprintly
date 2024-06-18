<script setup>
import { onBeforeMount } from 'vue';
import { useTaskApi, useTaskStore } from '@/Utils/task';
import { Link } from '@inertiajs/vue3';
import PaginatedTable from '@/Components/Table/PaginatedTable.vue';
import Badge from '@/Components/Shared/Badge.vue';
import ConfirmButton from '@/Components/Shared/ConfirmButton.vue';

const props = defineProps({
    headers: {
        type: Array,
        default: () => [
            { key: 'status', label: 'Status', class: 'uppercase text-xs font-bold' },
            { key: 'title', label: 'Title', class: 'text-xs' },
            { key: 'created_at', label: 'Created At', class: 'text-xs' },
            { key: 'updated_at', label: 'Updated At', class: 'text-xs' },
        ],
    },
    status: {
        default: null,
    },
    search: {
        default: null,
    },
    perPage: {
        type: Number,
        default: 10,
    },
    trashed: {
        default: null,
    },
    published: {
        default: null,
    },
    deletable: {
        type: Boolean,
        default: false
    },
    restorable: {
        type: Boolean,
        default: false
    },
    viewable: {
        type: Boolean,
        default: false
    },
    editable: {
        type: Boolean,
        default: false
    },
    progressible: {
        type: Boolean,
        default: false
    },
    publishable: {
        type: Boolean,
        default: false
    },
    parentId: {
        default: null,
    }
});

const taskStore = useTaskStore();

const {
    data,
    total,
    isRequesting,
    isEmpty,
    hasNextPage,
    refetch,
    next,
    destroy,
    restore,
    update,
    progress
} = await useTaskApi({
    status: props.status || taskStore.status,
    search: props.search || taskStore.search,
    per_page: props.perPage || taskStore.perPage,
    trashed: props.trashed,
    published: props.published,
    parent_id: props.parentId
});

onBeforeMount(refetch);
</script>

<template>
    <PaginatedTable :data="data?.pages" :headers="headers" :total="total" :is-empty="isEmpty" :is-requesting="isRequesting" :has-next-page="hasNextPage" label="tasks" @next="next">
        <template v-slot:['status']="{ data }">
            <Badge :hex="data.color">{{ data.name }}</Badge>
        </template>
        <template #actions="{ row }">
            <div class="flex gap-2">
                <Link v-if="viewable" :href="route('tasks.show', row.id)" class="bg-muted hover:bg-muted/80 text-white uppercase text-xs font-bold py-1 px-2 rounded">View</Link>
                <Link v-if="editable" :href="route('tasks.edit', row.id)" class="bg-primary hover:bg-primary/80 text-white uppercase text-xs font-bold py-1 px-2 rounded">Edit</Link>
                <ConfirmButton
                     class="bg-yellow-500 hover:bg-yellow-400 text-white uppercase text-xs font-bold py-1 px-2 rounded"
                     message="Are you sure you want to progress this?"
                     v-if="progressible && row.is_progressible"
                     @confirm="() => progress(row.id, { progress: true }).then(refetch)" >
                     Progress
                </ConfirmButton>
                <ConfirmButton
                    v-if="publishable"
                    class="bg-green-500 hover:bg-green-400 text-white uppercase text-xs font-bold py-1 px-2 rounded"
                    message="Are you sure you want to publish this?"
                    @confirm="() => update(row.id, { publish: true }).then(refetch)">
                    Publish
                </ConfirmButton>
                <ConfirmButton
                    v-if="deletable"
                    class="bg-red-500 hover:bg-red-400 text-white uppercase text-xs font-bold py-1 px-2 rounded"
                    message="Are you sure you want to delete this?"
                    @confirm="() => destroy(row.id).then(refetch)">
                    Delete
                </ConfirmButton>
                <ConfirmButton
                    v-if="restorable"
                    class="bg-green-500 hover:bg-green-400 text-white uppercase text-xs font-bold py-1 px-2 rounded"
                    message="Are you sure you want to restore this?"
                    @confirm="() => restore(row.id).then(refetch)">
                    Restore
                </ConfirmButton>
            </div>
        </template>
    </PaginatedTable>
</template>
