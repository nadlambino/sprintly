<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SubMenu from '@/Components/Tasks/SubMenu.vue';
import TaskTable from '@/Components/Tasks/TaskTable.vue';

const props = defineProps({
    statuses: Array,
});
</script>

<template>
    <Head title="Drafts" />

    <AuthenticatedLayout>
        <template #header>
            <SubMenu :draft="false" :statuses="statuses" :filterable="true" />
        </template>

        <div class="p-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-between items-center py-3">
                <h1 class="text-lg font-bold text-muted">Drafts</h1>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Suspense>
                    <TaskTable
                        :published="false"
                        :trashed="false"
                        :publishable="true"
                        :deletable="true"
                        :editable="true"
                    />
                </Suspense>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
