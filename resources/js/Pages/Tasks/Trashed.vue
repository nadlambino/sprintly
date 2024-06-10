<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SubMenu from '@/Components/Tasks/SubMenu.vue';
import TaskTable from '@/Components/Tasks/TaskTable.vue';

const props = defineProps({
    statuses: Array,
    days_before_deletion: Number|String,
});

const headers = [
    { key: 'status.name', label: 'Status', class: 'uppercase text-xs font-bold' },
    { key: 'title', label: 'Title', class: 'text-xs' },
    { key: 'deleted_at', label: 'Trashed At', class: 'text-xs' },
    { key: 'deleted_since', label: 'Trashed Since', class: 'text-xs' },
    { key: 'to_be_deleted_at', label: 'To Be Deleted At', class: 'text-xs' },
];
</script>

<template>
    <Head title="Trashed" />

    <AuthenticatedLayout>
        <template #header>
            <SubMenu :trashed="false" :filterable="true" :statuses="statuses" />
        </template>

        <div class="p-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-between items-center py-3">
                <h1 class="text-lg font-bold text-gray-700">Trashed</h1>
                <small class="text-xs text-muted">Note: If the server worker is running, your trashed tasks will be deleted after {{ days_before_deletion }} day(s) of being trashed.</small>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Suspense>
                    <TaskTable
                        :restorable="true"
                        :trashed="true"
                        :headers="headers"
                    />
                </Suspense>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
