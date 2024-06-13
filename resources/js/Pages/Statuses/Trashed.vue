<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SubMenu from '@/Components/Statuses/SubMenu.vue';
import StatusTable from '@/Components/Statuses/StatusTable.vue';

const headers = [
    { key: 'name', label: 'Name' },
    { key: 'description', label: 'Description' },
    { key: 'color', label: 'Color', as_color: true },
    { key: 'deleted_at', label: 'Trashed At', class: 'text-xs' },
    { key: 'deleted_since', label: 'Trashed Since', class: 'text-xs' },
];
</script>

<template>
    <Head title="Trashed" />

    <AuthenticatedLayout>
        <template #header>
            <SubMenu create list filters />
        </template>

        <div class="p-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-between items-center py-3">
                <h1 class="text-lg font-bold text-gray-700">Trashed</h1>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Suspense>
                    <StatusTable
                        :restorable="true"
                        :trashed="true"
                        :headers="headers"
                    />
                </Suspense>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
