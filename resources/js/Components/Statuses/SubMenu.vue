<script setup>
import { useTaskStore } from '@/Utils/task';
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AddIcon from '@/Icons/AddIcon.vue';
import TextInput from '@/Components/TextInput.vue';
import ButtonDropdown from '@/Components/ButtonDropdown.vue';

const props = defineProps({
    create: {
        type: Boolean,
        default: true
    },
    trashed: {
        type: Boolean,
        default: true
    },
    filters: {
        type: Boolean,
        default: true
    },
});

const taskStore = useTaskStore();

const sortOptions = [
    { label: '&uarr; Created', value: 'created_at' },
    { label: '&darr; Created', value: '-created_at' },
    { label: '&uarr; Title', value: 'title' },
    { label: '&darr; Title', value: '-title' },
];
</script>

<template>
    <div class="flex flex-col md:flex-row gap-5 justify-between">
        <div class="flex gap-5 items-center">
            <Link v-if="create" :href="route('tasks.create')">
                <PrimaryButton class="flex gap-1 justify-center items-center">
                    <AddIcon class="text-white" />
                    <span>Create</span>
                </PrimaryButton>
            </Link>
            <Link v-if="board" :href="route('tasks.index')" class="text-primary hover:text-primary/80 hover:underline">
                Board
            </Link>
            <Link v-if="draft" :href="route('tasks.drafts')" class="text-primary hover:text-primary/80 hover:underline">
                Drafts
            </Link>
            <Link v-if="trashed" :href="route('tasks.trashed')" class="text-primary hover:text-primary/80 hover:underline">
                Trashed
            </Link>
        </div>
        <div v-if="filters" class="flex flex-col md:flex-row gap-3 items-end md:items-center">
            <TextInput placeholder="Search by name or color..." class="min-w-full md:min-w-72" v-model="taskStore.search" />
            <div class="flex gap-3">
                <ButtonDropdown v-model="taskStore.sortBy" :label="`Sort: ${taskStore.sortBy}`" :options="sortOptions" />
            </div>
        </div>
    </div>
</template>
