<script setup>
import { useStatusStore } from '@/Utils/status';
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AddIcon from '@/Icons/AddIcon.vue';
import TextInput from '@/Components/TextInput.vue';
import ButtonDropdown from '@/Components/ButtonDropdown.vue';

const props = defineProps({
    create: {
        type: Boolean,
        default: false
    },
    list: {
        type: Boolean,
        default: false
    },
    trashed: {
        type: Boolean,
        default: false
    },
    filters: {
        type: Boolean,
        default: false
    },
});

const statusStore = useStatusStore();

const sortOptions = [
    { label: '&uarr; Order', value: 'order' },
    { label: '&darr; Order', value: '-order' },
    { label: '&uarr; Created', value: 'created_at' },
    { label: '&darr; Created', value: '-created_at' },
    { label: '&uarr; Name', value: 'name' },
    { label: '&darr; Name', value: '-name' },
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
            <Link v-if="list" :href="route('statuses.index')" class="text-primary hover:text-primary/80 hover:underline">
                List
            </Link>
            <Link v-if="trashed" :href="route('statuses.trashed')" class="text-primary hover:text-primary/80 hover:underline">
                Trashed
            </Link>
        </div>
        <div v-if="filters" class="flex flex-col md:flex-row gap-3 items-end md:items-center">
            <TextInput placeholder="Search by name or color..." class="min-w-full md:min-w-72" v-model="statusStore.search" />
            <div class="flex gap-3">
                <ButtonDropdown v-model="statusStore.sortBy" :label="`Sort: ${statusStore.sortBy}`" :options="sortOptions" />
            </div>
        </div>
    </div>
</template>
