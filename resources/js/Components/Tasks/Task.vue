<script setup>
import { Link } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import { computed } from 'vue';

const props = defineProps({
    task: {
        type: Object,
        required: true,
    }
});

const createdAt = computed(() => props.task.created_at ? dayjs(props.task.created_at).format('MMM D, YYYY h:mm A') : '-');
const publishedAt = computed(() => props.task.published_at ? dayjs(props.task.published_at).format('MMM D, YYYY h:mm A') : '-');
</script>

<template>
    <div class="flex flex-col gap-5 border shadow-md rounded-md p-3">
        <Link :href="route('tasks.edit', task.id)" class="text-blue-600 hover:text-blue-800">
            <h1 class="truncate font-bold text-lg" :title="task.title">{{ task.title }}</h1>
        </Link>
        <div>
            <p class="line-clamp-3">{{ task.content }}</p>
        </div>
        <div class="flex flex-col text-muted">
            <div class="flex justify-between">
                <small>Created At</small>
                <small>{{ createdAt }}</small>
            </div>
            <div class="flex justify-between">
                <small>Published At</small>
                <small>{{ publishedAt }}</small>
            </div>
        </div>
    </div>
</template>
