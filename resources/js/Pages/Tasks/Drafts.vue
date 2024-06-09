<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SubMenu from '@/Components/Tasks/SubMenu.vue';
import TaskTable from '@/Components/Tasks/TaskTable.vue';

const props = defineProps({
    statuses: Array,
});

const headers = [
    { key: 'status.name', label: 'Status', class: 'uppercase text-xs font-bold' },
    { key: 'title', label: 'Title', class: 'text-xs' },
    { key: 'created_at', label: 'Created At', class: 'text-xs' },
    { key: 'updated_at', label: 'Updated At', class: 'text-xs' },
];
</script>

<template>
    <Head title="Drafts" />

    <AuthenticatedLayout>
        <template #header>
            <SubMenu :draft="false" :statuses="statuses" :filterable="true" />
        </template>

        <div class="py-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-5">
                <h1 class="text-lg font-bold text-muted">Drafts</h1>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Suspense>
                    <TaskTable
                        :published="false"
                        :trashed="false"
                        :headers="headers"
                        :publishable="true"
                        :deletable="true"
                        :editable="true"
                    />
                </Suspense>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
