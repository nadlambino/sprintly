<script setup>
import { ref } from 'vue';
import Modal from '@/Components/Shared/Modal.vue';

const props = defineProps({
    src: {
        type: String,
        required: true
    },
    alt: {
        type: String,
    },
    width: {
        type: String,
    },
    height: {
        type: String,
    },
    class: {
        type: String,
    },
    style: {
        type: String,
    },
    id: {
        type: String,
    },
});

const show = ref(false);
</script>

<template>
    <img v-bind="props" @click="show = true" class="cursor-pointer" />
    <Modal :show="show" @close="show = false">
        <img :src="props.src" :alt="props.alt" class="w-full h-full object-contain" />
    </Modal>
</template>
