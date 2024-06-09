<script setup>
import { useTasks } from '@/Composables/useTasks';
import { useTasksStore } from '@/Stores/useTasksStore';
import { Link } from '@inertiajs/vue3';
import PaginatedTable from '@/Components/Table/PaginatedTable.vue';

const props = defineProps({
    headers: {
        type: Array,
        required: true
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
    editable: {
        type: Boolean,
        default: false
    },
    publishable: {
        type: Boolean,
        default: false
    }
});

const tasksStore = useTasksStore();

const { data, total, isRequesting, isEmpty, hasNextPage, refetch, next } = await useTasks({
    status: props.status || tasksStore.status,
    search: props.search || tasksStore.search,
    per_page: props.perPage || tasksStore.perPage,
    trashed: props.trashed,
    published: props.published
});

const destroy = (id) => {
    window.axios.delete(route('api.tasks.destroy', { task: id }))
        .then(() => refetch());
}

const publish = (id) => {
    window.axios.put(route('api.tasks.update', { task: id }), { publish: true })
        .then(() => refetch());
}

const restore = (id) => {
    window.axios.put(route('api.tasks.restore', { task: id }))
        .then(() => refetch());
}
</script>

<template>
    <PaginatedTable :data="data?.pages" :headers="headers" :total="total" :is-empty="isEmpty" :is-requesting="isRequesting" :has-next-page="hasNextPage" label="tasks" @next="next">
        <template #actions="{ row }">
            <div class="flex gap-2">
                <Link v-if="editable" :href="route('tasks.edit', row.id)" class="bg-primary hover:bg-primary/80 text-white uppercase text-xs font-bold py-1 px-2 rounded">Edit</Link>
                <button v-if="publishable" @click="() => publish(row.id)" class="bg-green-500 hover:bg-green-400 text-white uppercase text-xs font-bold py-1 px-2 rounded">Publish</button>
                <button v-if="deletable" @click="() => destroy(row.id)" class="bg-red-500 hover:bg-red-400 text-white uppercase text-xs font-bold py-1 px-2 rounded">Delete</button>
                <button v-if="restorable" @click="() => restore(row.id)" class="bg-green-500 hover:bg-green-400 text-white uppercase text-xs font-bold py-1 px-2 rounded">Restore</button>
            </div>
        </template>
    </PaginatedTable>
</template>
