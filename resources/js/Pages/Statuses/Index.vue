<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatusTable from '../../Components/Statuses/StatusTable.vue';
</script>

<template>
    <Head title="Statuses" />

    <AuthenticatedLayout>

        <div class="p-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-between items-center py-3">
                <h1 class="text-lg font-bold text-gray-700">Statuses</h1>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <StatusTable />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
