<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SubMenu from '@/Components/Tasks/SubMenu.vue';
import TaskTable from '@/Components/Tasks/TaskTable.vue';

const headers = [
    { key: 'status.name', label: 'Status', class: 'uppercase text-xs font-bold' },
    { key: 'title', label: 'Title', class: 'text-xs' },
    { key: 'created_at', label: 'Created At', class: 'text-xs' },
    { key: 'updated_at', label: 'Updated At', class: 'text-xs' },
    { key: 'deleted_at', label: 'Deleted At', class: 'text-xs' },
];
</script>

<template>
    <Head title="Trashed" />

    <AuthenticatedLayout>
        <template #header>
            <SubMenu :draft="false" />
        </template>

        <div class="py-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-5">
                <h1 class="text-lg font-bold text-muted">Trashed</h1>
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
