<script setup>
import { computed, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import DeleteIcon from '@/Icons/DeleteIcon.vue';
import Alert from '@/Components/Alert.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    task: {
        type: Object,
        required: true,
    }
});

const emit = defineEmits(['destroy']);

const showConfirmDelete = ref(false);
const cardClass = computed(() => {
    if (props.task.status.name === 'todo') {
        return 'border-t-primary';
    }

    if (props.task.status.name === 'in progress') {
        return 'border-t-yellow-500';
    }

    if (props.task.status.name === 'done') {
        return 'border-t-green-500';
    }

    return 'border-t-gray-500';
})

const destroy = () => {
    window.axios.delete(route('api.tasks.destroy', { task: props.task.id }))
        .then(() => emit('destroy', props.task.id));
}
</script>

<template>
    <Alert :show="showConfirmDelete" title="Confirm Delete" message="Are you sure you want to delete this task?" @close="showConfirmDelete = false">
        <template #actions>
            <PrimaryButton class="flex gap-1 justify-center items-center" @click="destroy">
                Confirm
            </PrimaryButton>
        </template>
    </Alert>

    <div class="flex flex-col gap-5 border shadow-md rounded-md p-3 border-t-4" :class="cardClass">
        <div class="flex justify-between items-center">
            <div class="w-[90%]">
                <Link :href="route('tasks.edit', task.id)" class="text-blue-600 hover:text-blue-800">
                    <h1 class="font-bold text-lg truncate" :title="task.title">{{ task.title }}</h1>
                </Link>
            </div>
            <button type="button" @click="showConfirmDelete = true">
                <DeleteIcon class="text-gray-500"/>
            </button>
        </div>
        <div>
            <p class="line-clamp-3 text-gray-700" :title="task.content">{{ task.content }}</p>
        </div>
        <div v-if="task.images.length > 0">
            <img :src="task.images[0].path" :alt="task.title" class="w-full object-cover" />
            <small class="text-muted" v-if="task.images.length > 1"> and {{ task.images.length - 1 }} more image(s)</small>
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
