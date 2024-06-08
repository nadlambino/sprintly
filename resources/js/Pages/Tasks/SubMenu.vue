<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AddIcon from '../../Icons/AddIcon.vue';
import { Link } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import { useTasksStore } from '@/Stores/useTasksStore';

const tasksStore = useTasksStore();

</script>

<template>
    <div class="flex gap-5 justify-between">
        <Link :href="route('tasks.create')">
            <PrimaryButton class="flex gap-1 justify-center items-center">
                <AddIcon class="text-white" />
                <span>Create</span>
            </PrimaryButton>
        </Link>
        <div>
            <TextInput placeholder="Search..." class="w-72" v-model="tasksStore.search" />
        </div>
    </div>
</template>
@/Stores/useTasksStore
