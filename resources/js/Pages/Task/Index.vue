<script setup>
import { Head } from '@inertiajs/vue3';
import { useStorage } from '@vueuse/core';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SubMenu from '@/Components/Tasks/SubMenu.vue';
import Kanban from '@/Components/Tasks/Kanban.vue';
import TaskTable from '@/Components/Tasks/TaskTable.vue';

const kanban = useStorage('kanban', true);
</script>

<template>
    <Head title="Tasks" />

    <AuthenticatedLayout>
        <template #header>
            <SubMenu :board="false" :toggleable="true" v-model:kanban="kanban" />
        </template>

        <div class="p-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-between items-center py-3">
                <h1 class="text-lg font-bold text-gray-700">Board</h1>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Kanban v-if="kanban" />
                <Suspense>
                    <TaskTable
                        v-if="!kanban"
                        :published="true"
                        :trashed="false"
                        :deletable="true"
                        :editable="true"
                    />
                </Suspense>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
