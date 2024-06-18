<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import Badge from '@/Components/Shared/Badge.vue';
import ViewableImage from '@/Components/Shared/ViewableImage.vue';

const props = defineProps({
    task: Object,
});

const images = computed(() => props.task?.images?.map((image) => window.location.origin + '/' + image.path));
</script>

<template>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col justify-between py-3">
        <div class="bg-white p-5 rounded-lg shadow-lg">
            <div class="flex justify-between items-center">
                <Link :href="route('tasks.show', task?.id)"><h1 class="text-lg font-bold text-blue-600 hover:text-blue-800 capitalize underline">{{ task?.title }}</h1></Link>
                <Badge :hex="task?.status?.color">{{ task?.status?.name }}</Badge>
            </div>
            <div class="flex justify-between items-center mt-1">
                <div class="flex gap-2 text-[13px]">
                    <small class="text-muted">Created at {{ task?.created_at }}</small>
                    <small class="text-muted">Published at {{ task?.published_at }}</small>
                </div>
                <Link :href="route('tasks.edit', task?.id)" class="text-primary hover:text-primary/80 hover:underline uppercase text-[13px]">Edit</Link>
            </div>
            <p class="text-gray-700 mt-5">{{ task?.content }}</p>
            <div class="flex flex-wrap gap-5 mt-5 justify-center">
                <ViewableImage v-for="(image, index) in images" :key="index" :src="image" :alt="task.title" class="object-cover border border-gray-200 w-52 aspect-square" />
            </div>
        </div>
    </div>
</template>
