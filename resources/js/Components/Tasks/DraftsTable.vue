<script setup>
import { useTasks } from '@/Composables/useTasks';
import { useTasksStore } from '@/Stores/useTasksStore';
import { Link } from '@inertiajs/vue3';
import PaginatedTable from '@/Components/Table/PaginatedTable.vue';

const headers = [
    { key: 'status.name', label: 'Status', class: 'uppercase text-xs font-bold' },
    { key: 'title', label: 'Title', class: 'text-xs' },
    { key: 'created_at', label: 'Created At', class: 'text-xs' },
    { key: 'updated_at', label: 'Updated At', class: 'text-xs' },
];

const tasksStore = useTasksStore();

const { data, total, isRequesting, isEmpty, hasNextPage, refetch, next } = await useTasks({
    status: tasksStore.status,
    published: false,
    search: tasksStore.search,
    per_page: tasksStore.perPage
});

const destroy = (id) => {
    window.axios.delete(route('api.tasks.destroy', { task: id }))
        .then(() => refetch());
}

const publish = (id) => {
    window.axios.put(route('api.tasks.update', { task: id }), { publish: true })
        .then(() => refetch());
}
</script>

<template>

    <PaginatedTable :data="data?.pages" :headers="headers" :total="total" :is-empty="isEmpty" :is-requesting="isRequesting" :has-next-page="hasNextPage" @next="next">
        <template #actions="{ row }">
            <div class="flex gap-2">
                <Link :href="route('tasks.edit', row.id)" class="bg-blue-500 hover:bg-blue-700 text-white uppercase text-xs font-bold py-1 px-2 rounded">Edit</Link>
                <button @click="() => publish(row.id)" class="bg-green-500 hover:bg-green-700 text-white uppercase text-xs font-bold py-1 px-2 rounded">Publish</button>
                <button @click="() => destroy(row.id)" class="bg-red-500 hover:bg-red-700 text-white uppercase text-xs font-bold py-1 px-2 rounded">Delete</button>
            </div>
        </template>
    </PaginatedTable>
</template>
