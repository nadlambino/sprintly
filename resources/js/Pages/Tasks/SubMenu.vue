<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AddIcon from '../../Icons/AddIcon.vue';
import { Link } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import { useTasksStore } from '@/Stores/useTasksStore';
import ButtonDropdown from '@/Components/ButtonDropdown.vue';

const tasksStore = useTasksStore();
const sortOptions = [
    { label: 'Created At (ASC)', value: 'created_at' },
    { label: 'Created At (DESC)', value: '-created_at' },
    { label: 'Title (ASC)', value: 'title' },
    { label: 'Title (DESC)', value: '-title' },
];
const pageOptions = [
    { label: '10 Per Page', value: 10 },
    { label: '20 Per Page', value: 20 },
    { label: '50 Per Page', value: 50 },
    { label: '100 Per Page', value: 100 },
]
</script>

<template>
    <div class="flex gap-5 justify-between">
        <div class="flex gap-3 items-center">
            <Link :href="route('tasks.create')">
                <PrimaryButton class="flex gap-1 justify-center items-center">
                    <AddIcon class="text-white" />
                    <span>Create</span>
                </PrimaryButton>
            </Link>
            <Link :href="route('tasks.drafts')">
                Drafts
            </Link>
        </div>
        <div class="flex gap-2">
            <TextInput placeholder="Search by title..." class="w-72" v-model="tasksStore.search" />
            <ButtonDropdown v-model="tasksStore.sortBy" :label="`Sort By: ${tasksStore.sortBy}`" :options="sortOptions" />
            <ButtonDropdown v-model="tasksStore.perPage" :label="`Per Page: ${tasksStore.perPage}`" :options="pageOptions" />
        </div>
    </div>
</template>
