<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex flex-col gap-1 text-center border shadow-md rounded-md p-3 border-dashed">
        <p class="cursor-default text-muted">You do not have any task here.</p>
        <Link :href="route('tasks.create')" class="text-blue-500 hover:underline">Create a new task</Link>
    </div>
</template>
