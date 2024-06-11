<script setup>
import { computed, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useTaskApi } from '@/Utils/task';
import DeleteIcon from '@/Icons/DeleteIcon.vue';
import Alert from '@/Components/Alert.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    task: {
        type: Object,
        required: true,
    },
    accentColor: {
        type: String,
        default: 'gray-500',
    }
});

const emit = defineEmits(['destroy']);

const showConfirmDelete = ref(false);
const images = computed(() => Array.from(props.task.images).map((image) => window.location.origin + '/' + image.path));

const { destroy } = useTaskApi  ();
</script>

<template>
    <Alert :show="showConfirmDelete" title="Confirm Delete" message="Are you sure you want to delete this task? This will also delete all of its subtasks." @close="showConfirmDelete = false">
        <template #actions>
            <PrimaryButton class="flex gap-1 justify-center items-center" @click="() => destroy(task.id).then(() => emit('destroy', task.id))">
                Confirm
            </PrimaryButton>
        </template>
    </Alert>

    <div :data-id="task.id" :data-status="task.status.name" class="flex flex-col gap-5 border shadow-lg rounded-md p-3 border-t-4 hover:cursor-grab" :class="accentColor">
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
        <div class="flex flex-col text-muted text-xs">
            <div class="flex justify-between">
                <small>Created At</small>
                <small>{{ task.created_at }}</small>
            </div>
            <div class="flex justify-between">
                <small>Published At</small>
                <small>{{ task.published_at }}</small>
            </div>
        </div>
    </div>
</template>

<style scoped>
.gray-500 {
    @apply border-t-gray-500;
}

.primary {
    @apply border-t-primary;
}

.yellow-500 {
    @apply border-t-yellow-500;
}

.green-500 {
    @apply border-t-green-500;
}
</style>
