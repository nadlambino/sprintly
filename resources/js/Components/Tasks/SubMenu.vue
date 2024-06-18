<script setup>
import { computed, onMounted, watch } from 'vue';
import { useTaskStore } from '@/Utils/task';
import { useStatusStore } from '@/Utils/status';
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/Shared/PrimaryButton.vue';
import AddIcon from '@/Icons/AddIcon.vue';
import TextInput from '@/Components/Shared/TextInput.vue';
import ButtonDropdown from '@/Components/Shared/ButtonDropdown.vue';
import Toggle from '@/Components/Shared/Toggle.vue';
import InputLabel from '@/Components/Shared/InputLabel.vue';

const props = defineProps({
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

const taskStore = useTaskStore();

const sortOptions = [
    { label: '&uarr; Created', value: 'created_at' },
    { label: '&darr; Created', value: '-created_at' },
    { label: '&uarr; Title', value: 'title' },
    { label: '&darr; Title', value: '-title' },
];
const pageOptions = [
    { label: '5', value: 5 },
    { label: '10', value: 10 },
    { label: '20', value: 20 },
    { label: '50', value: 50 },
    { label: '100', value: 100 },
];
const { statuses } = useStatusStore();
const statusOptions = computed(() => {
    let options =  statuses?.map(status => {
        return {
            label: status.name,
            value: status.name
        }
    }) ?? [];

    options.unshift({ label: 'All', value: 'all' });

    return options;
});

const resetStatusFilter = () => {
    taskStore.status = props.filterable || !kanban.value ? 'all' : taskStore.status;
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
            <TextInput placeholder="Search by title..." class="min-w-full md:min-w-72" v-model="taskStore.search" />
            <div class="flex gap-3">
                <ButtonDropdown v-if="filterable || !kanban" v-model="taskStore.status" :options="statusOptions" placement="left-0" />
                <ButtonDropdown v-model="taskStore.sortBy" :options="sortOptions" />
                <ButtonDropdown v-model="taskStore.perPage" :label="`Paginate: ${taskStore.perPage}`" :options="pageOptions" />
                <div v-if="toggleable" class="flex flex-col gap-1">
                    <InputLabel value="Kanban" class="text-[10px] uppercase text-muted" />
                    <Toggle v-model="kanban" />
                </div>
            </div>
        </div>
    </div>
</template>
