<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    status: String
})
</script>

<template>
    <div class="flex flex-col gap-1 text-center border shadow-md rounded-md p-3 border-dashed">
        <p class="cursor-default text-muted">You do not have any task here.</p>
        <Link :href="route('tasks.create', { status })" class="text-blue-500 hover:underline">Create a new {{ status }} task</Link>
    </div>
</template>
