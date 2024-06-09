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
import Toggle from '../Toggle.vue';
import { ref } from 'vue';
import InputLabel from '../InputLabel.vue';

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
    filters: {
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
    },
    toggleable: {
        type: Boolean,
        default: false
    }
});

const tasksStore = useTasksStore();
const sortOptions = [
    { label: '&uarr; Created', value: 'created_at' },
    { label: '&darr; Created', value: '-created_at' },
    { label: '&uarr; Title', value: 'title' },
    { label: '&darr; Title', value: '-title' },
];
const pageOptions = [
    { label: '10 Tasks', value: 10 },
    { label: '20 Tasks', value: 20 },
    { label: '50 Tasks', value: 50 },
    { label: '100 Tasks', value: 100 },
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
    tasksStore.status = props.filterable || !kanban.value ? 'all' : tasksStore.status;
};

onMounted(resetStatusFilter);

watch(() => props.filterable, resetStatusFilter);

const kanban = defineModel('kanban', { default: false });

watch(kanban, resetStatusFilter);
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
            <TextInput placeholder="Search by title..." class="min-w-full md:min-w-72" v-model="tasksStore.search" />
            <div class="flex gap-3">
                <ButtonDropdown v-if="filterable || !kanban" v-model="tasksStore.status" :label="`Status: ${tasksStore.status}`" :options="statusOptions" placement="left-0" />
                <ButtonDropdown v-model="tasksStore.sortBy" :label="`Sort: ${tasksStore.sortBy}`" :options="sortOptions" />
                <ButtonDropdown v-model="tasksStore.perPage" :label="`Paginate: ${tasksStore.perPage}`" :options="pageOptions" />
                <div v-if="toggleable" class="flex flex-col gap-1">
                    <InputLabel value="Kanban" class="text-[10px] uppercase text-muted" />
                    <Toggle v-model="kanban" />
                </div>
            </div>
        </div>
    </div>
</template>
