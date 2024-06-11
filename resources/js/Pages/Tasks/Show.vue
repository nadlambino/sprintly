<script setup>
import { Head } from '@inertiajs/vue3';
import { useStorage } from '@vueuse/core';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SubMenu from '@/Components/Tasks/SubMenu.vue';
import TaskTable from '@/Components/Tasks/TaskTable.vue';
import Kanban from '@/Components/Tasks/Kanban.vue';
import View from '../../Components/Tasks/View.vue';

const props = defineProps({
    task: {
        type: Object,
    },
    statuses: {
        type: Array,
        required: true,
    },
});

const kanban = useStorage('kanban', true);
</script>

<template>
    <Head :title="task?.title" />

    <AuthenticatedLayout>
        <template #header>
            <SubMenu :statuses="statuses" :filterable="true" v-model:kanban="kanban" :toggleable="true" />
        </template>

        <div class="p-5">
            <View :task="task" />
        </div>

        <div class="p-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-between items-center py-3">
                <h1 class="text-lg font-bold text-gray-700">Subtasks</h1>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Kanban v-if="kanban" :parent-id="task?.id" />
                <Suspense>
                    <TaskTable
                        v-if="!kanban"
                        :published="true"
                        :trashed="false"
                        :viewable="true"
                        :editable="true"
                        :deletable="true"
                        :parent-id="task?.id"
                    />
                </Suspense>
            </div>
        </div>

        <div v-if="task.parent" class="p-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-between items-center py-3">
                <h1 class="text-lg font-bold text-gray-700">Parent</h1>
            </div>
            <View :task="task.parent" />
        </div>
    </AuthenticatedLayout>
</template>
