<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AddIcon from '../../Icons/AddIcon.vue';
import { Link } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import { useTasksStore } from '@/Stores/useTasksStore';
import ButtonDropdown from '@/Components/ButtonDropdown.vue';

const tasksStore = useTasksStore();
const options = [
    { label: 'Created At (ASC)', value: 'created_at' },
    { label: 'Created At (DESC)', value: '-created_at' },
    { label: 'Title (ASC)', value: 'title' },
    { label: 'Title (DESC)', value: '-title' },
];
</script>

<template>
    <div class="flex gap-5 justify-between">
        <Link :href="route('tasks.create')">
            <PrimaryButton class="flex gap-1 justify-center items-center">
                <AddIcon class="text-white" />
                <span>Create</span>
            </PrimaryButton>
        </Link>
        <div class="flex gap-2">
            <TextInput placeholder="Search..." class="w-72" v-model="tasksStore.search" />
            <ButtonDropdown v-model="tasksStore.sortBy" :label="`Sort By: ${tasksStore.sortBy}`" :options="options" />
        </div>
    </div>
</template>
@/Stores/useTasksStore
