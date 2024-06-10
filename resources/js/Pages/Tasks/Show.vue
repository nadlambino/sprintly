<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { useStorage } from '@vueuse/core';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SubMenu from '@/Components/Tasks/SubMenu.vue';
import TaskTable from '@/Components/Tasks/TaskTable.vue';
import Kanban from '@/Components/Tasks/Kanban.vue';

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
const images = computed(() => props.task?.images?.map((image) => window.location.origin + '/' + image.path));
const statusBadgeClass = computed(() => {
    switch (props.task?.status?.name) {
        case 'todo':
            return 'bg-primary/10 text-primary';
        case 'in progress':
            return 'bg-yellow-100 text-yellow-500';
        case 'done':
            return 'bg-green-100 text-green-500';
        default:
            return 'bg-gray-100 text-gray-500';
    }
});
</script>

<template>
    <Head :title="task?.title" />

    <AuthenticatedLayout>
        <template #header>
            <SubMenu :statuses="statuses" :filterable="true" v-model:kanban="kanban" :toggleable="true" />
        </template>

        <div class="p-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col justify-between py-3">
                <div class="bg-white p-5 rounded-lg shadow-lg">
                    <div class="flex justify-between items-center">
                        <Link :href="route('tasks.edit', task?.id)"><h1 class="text-lg font-bold text-blue-600 hover:text-blue-800 capitalize underline">{{ task?.title }}</h1></Link>
                        <small class="px-2 py-1 rounded text-[10px] uppercase font-bold" :class="statusBadgeClass">{{ task?.status.name }}</small>
                    </div>
                    <div class="flex justify-between items-center mt-1">
                        <div class="flex gap-2 text-[13px]">
                            <small class="text-muted">Created at {{ task?.created_at }}</small>
                            <small class="text-muted">Published at {{ task?.published_at }}</small>
                        </div>
                        <Link :href="route('tasks.edit', task?.id)" class="text-primary hover:text-primary/80 hover:underline uppercase text-[13px]">Edit</Link>
                    </div>
                    <p class="text-gray-700 mt-5">{{ task?.content }}</p>
                    <div class="flex flex-wrap gap-5 mt-5">
                        <img v-for="(image, index) in images" :key="index" :src="image" :alt="task.title" class="w-full object-cover w-52 aspect-square" />
                    </div>
                </div>
            </div>
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
    </AuthenticatedLayout>
</template>
