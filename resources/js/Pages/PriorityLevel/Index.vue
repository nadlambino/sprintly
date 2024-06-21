<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PriorityLevelTable from '@/Components/PriorityLevels/PriorityLevelTable.vue';
import SubMenu from '@/Components/PriorityLevels/SubMenu.vue';
</script>

<template>
    <Head title="Statuses" />

    <AuthenticatedLayout>
        <template #header>
            <SubMenu create filters />
        </template>

        <div class="p-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-between items-center py-3">
                <h1 class="text-lg font-bold text-gray-700">Priority Levels</h1>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <PriorityLevelTable deletable editable />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
