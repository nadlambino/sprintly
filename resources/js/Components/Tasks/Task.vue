<script setup>
import { computed, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useTaskApi, useTimeSpent } from '@/Utils/task';
import DeleteIcon from '@/Icons/DeleteIcon.vue';
import Alert from '@/Components/Shared/Alert.vue';
import PrimaryButton from '@/Components/Shared/PrimaryButton.vue';
import Badge from '../Shared/Badge.vue';

const props = defineProps({
    task: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['destroy']);

const showConfirmDelete = ref(false);
const images = computed(() => Array.from(props.task.images).map((image) => window.location.origin + '/' + image.path));

const { destroy } = useTaskApi  ();

const timespent = useTimeSpent();

const showDates = ref(false);
</script>

<template>
    <Alert :show="showConfirmDelete" title="Confirm Delete" message="Are you sure you want to delete this task? This will also delete all of its subtasks." @close="showConfirmDelete = false">
        <template #actions>
            <PrimaryButton class="flex gap-1 justify-center items-center" @click="() => destroy(task.id).then(() => emit('destroy', task.id))">
                Confirm
            </PrimaryButton>
        </template>
    </Alert>

    <div :data-id="task.id" :data-status="task.status.name" class="flex flex-col gap-5 border shadow-lg rounded-md p-3 border-t-4 hover:cursor-grab" :style="{ 'border-top-color': task?.status?.color }">
        <div class="w-full">
            <Link v-if="task.parent" :href="route('tasks.show', task.parent.id)" class="text-blue-600 hover:text-blue-800 text-xs line-clamp-1">{{ task.parent.title }}</Link>
            <div class="flex justify-between items-center">
                <div class="w-[87%]">
                    <Link :href="route('tasks.show', task.id)" class="text-blue-600 hover:text-blue-800">
                        <h1 class="font-bold text-lg truncate" :title="task.title">{{ task.title }}</h1>
                    </Link>
                </div>
                <button type="button" @click="showConfirmDelete = true">
                    <DeleteIcon class="text-gray-500 hover:text-red-500"/>
                </button>
            </div>
            <Badge :hex="task.priority_level.color">{{ task.priority_level.name }}</Badge>
        </div>
        <div>
            <p class="line-clamp-3 text-gray-700" :title="task.content">{{ task.content }}</p>
        </div>
        <div v-if="task.children?.length > 0">
            <Link :href="route('tasks.show', task.id)" class="text-blue-600 hover:text-blue-800 text-xs">View subtasks</Link>
        </div>
        <div v-if="images.length > 0">
            <img :src="images[0]" :alt="task.title" class="w-full object-cover" />
            <small class="text-muted" v-if="images.length > 1"> and {{ images.length - 1 }} more image(s)</small>
        </div>
        <small class="cursor-pointer text-primary hover:text-primary/80 hover:underline" @click="showDates = !showDates">{{  showDates ? 'Hide' : 'Show' }} Dates</small>
        <div class="flex flex-col text-muted text-xs" v-show="showDates">
            <div class="flex justify-between">
                <small>Created At</small>
                <small>{{ task.created_at }}</small>
            </div>
            <div class="flex justify-between">
                <small>Published At</small>
                <small>{{ task.published_at }}</small>
            </div>
            <div class="flex justify-between">
                <small>Start At</small>
                <small>{{ task.start_at }}</small>
            </div>
            <div class="flex justify-between">
                <small>Due At</small>
                <small>{{ task.due_at }}</small>
            </div>
            <div class="flex justify-between">
                <small>Started At</small>
                <small>{{ task.started_at }}</small>
            </div>
            <div class="flex justify-between">
                <small>Ended At</small>
                <small>{{ task.ended_at }}</small>
            </div>
            <div class="flex justify-between">
                <small>Time Spent</small>
                <small>{{ timespent(task.time_spent) }}</small>
            </div>
        </div>
    </div>
</template>
