<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SubMenu from '@/Components/Tasks/SubMenu.vue';
import DraftsTable from '@/Components/Tasks/DraftsTable.vue';
</script>

<template>
    <Head title="Drafts" />

    <AuthenticatedLayout>
        <template #header>
            <SubMenu :draft="false" />
        </template>

        <div class="py-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-5">
                <h1 class="text-lg font-bold text-muted">Drafts</h1>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Suspense>
                    <DraftsTable />
                </Suspense>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
