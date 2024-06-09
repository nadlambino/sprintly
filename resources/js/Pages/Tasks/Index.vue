<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SubMenu from '@/Components/Tasks/SubMenu.vue';
import Kanban from '@/Components/Tasks/Kanban.vue';
</script>

<template>
    <Head title="Tasks" />

    <AuthenticatedLayout>
        <template #header>
            <SubMenu />
        </template>

        <div class="py-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-5">
                <h1 class="text-lg font-bold text-muted">Drafts</h1>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Kanban />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
