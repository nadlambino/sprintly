<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AddIcon from '../../Icons/AddIcon.vue';
import NavLink from '@/Components/NavLink.vue';
</script>

<template>
    <div class="flex gap-5">
        <NavLink :href="route('tasks.create')" unstyled>
            <PrimaryButton class="flex gap-1 justify-center items-center">
                <AddIcon class="text-white" />
                <span>Create</span>
            </PrimaryButton>
        </NavLink>
    </div>
</template>
