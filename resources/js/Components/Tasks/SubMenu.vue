<script setup>
import { useTasksStore } from '@/Stores/useTasksStore';
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AddIcon from '@/Icons/AddIcon.vue';
import TextInput from '@/Components/TextInput.vue';
import ButtonDropdown from '@/Components/ButtonDropdown.vue';
import { computed } from 'vue';
import { watch } from 'vue';
import { onMounted } from 'vue';

const props = defineProps({
    statuses: Array,
    create: {
        type: Boolean,
        default: true
    },
    board: {
        type: Boolean,
        default: true
    },
    draft: {
        type: Boolean,
        default: true
    },
    trashed: {
        type: Boolean,
        default: true
    },
    filterable: {
        type: Boolean,
        default: false
    }
});

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
];
const statusOptions = computed(() => {
    let options =  props.statuses?.map(status => {
        return {
            label: status.name,
            value: status.name
        }
    }) ?? [];

    options.unshift({ label: 'All', value: 'all' });

    return options;
});

const resetStatusFilter = () => {
    tasksStore.status = props.filterable ? 'all' : null;
};

onMounted(resetStatusFilter);

watch(() => props.filterable, resetStatusFilter);
</script>

<template>
    <div class="flex gap-5 justify-between">
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
        <div class="flex gap-2">
            <TextInput placeholder="Search by title..." class="w-72" v-model="tasksStore.search" />
            <ButtonDropdown v-if="filterable" v-model="tasksStore.status" :label="`Status: ${tasksStore.status}`" :options="statusOptions" />
            <ButtonDropdown v-model="tasksStore.sortBy" :label="`Sort: ${tasksStore.sortBy}`" :options="sortOptions" />
            <ButtonDropdown v-model="tasksStore.perPage" :label="`Paginate: ${tasksStore.perPage}`" :options="pageOptions" />
        </div>
    </div>
</template>