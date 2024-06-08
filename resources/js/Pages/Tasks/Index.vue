<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import SubMenu from './SubMenu.vue';
import Kanban from '@/Components/Tasks/Kanban.vue';
</script>

<template>
    <Head title="Tasks" />

    <AuthenticatedLayout>
        <template #header>
            <SubMenu />
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Kanban />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
