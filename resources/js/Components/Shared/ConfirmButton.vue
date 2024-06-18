<script setup>
import { ref, useSlots } from 'vue';
import Alert from '@/Components/Shared/Alert.vue';
import PrimaryButton from '@/Components/Shared/PrimaryButton.vue';

const props = defineProps({
    text: String,
    message: {
        type: String,
        default: () => 'Are you sure you want to perform this action?',
    },
    title: {
        type: String,
        default: 'Confirm',
    },
    id: String,
    class: String,
    style: String,
});

const emit = defineEmits(['confirm']);

const proceed = ref(false);

const slot = useSlots();

</script>

<template>
    <button @click="proceed = true" v-bind="props">
        <slot v-if="slot.default" />
        <span v-else>{{ text }}</span>
    </button>
    <Alert :show="proceed" @close="proceed = false" :title="title" :message="message">
        <template #actions>
            <PrimaryButton @click="emit('confirm'); proceed = false">
                Confirm
            </PrimaryButton>
        </template>
    </Alert>
</template>
