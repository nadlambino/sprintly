<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AddIcon from '../../Icons/AddIcon.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex gap-5">
        <Link :href="route('tasks.create')">
            <PrimaryButton class="flex gap-1 justify-center items-center">
                <AddIcon class="text-white" />
                <span>Create</span>
            </PrimaryButton>
        </Link>
    </div>
</template>
