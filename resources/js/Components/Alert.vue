<script setup>
import { ref, watch } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from './Modal.vue';

const props = defineProps({
    show: Boolean,
    message: String,
    title: {
        type: String,
        default: 'Alert',
    }
});

const visible = ref(props.show);

watch(() => props.show, () => {
    visible.value = props.show;
});
</script>

<template>
    <Modal :show="visible" :closeable="true" @close="visible = false" max-width="md">
        <div class="p-3 border-top border">
            <h1 class="font-bold text-sm text-gray-700 tracking-wider uppercase">{{ title }}</h1>
        </div>
        <div class="p-3 py-5">
            <h1 class="text-base text-muted text-gray-700">{{ message }}</h1>
        </div>
        <div class="p-3 border-top border flex justify-end gap-2">
            <SecondaryButton @click="visible = false">Close</SecondaryButton>
            <slot name="actions" />
        </div>
    </Modal>
</template>
