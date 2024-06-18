<script setup>
import { ref, watch, onUpdated } from 'vue';
import { debouncedRef } from '@vueuse/core';
import { onClickOutside } from '@vueuse/core';
import TextInput from '@/Components/Shared/TextInput.vue';

const props = defineProps({
    options: Array,
    id: String,
    placeholder: String,
});

const emits = defineEmits(['change']);

const model = defineModel();
const show = ref(false);
const input = ref(null);
const search = ref('');
const selected = ref('');

const setDefaultSelected = () => {
    if (props.options?.length) {
        const option = props.options.find(option => option.value === model.value);

        if (option) selected.value = option?.label;
    }
}

watch(() => props.options, setDefaultSelected, { immediate: true });

const container = ref(null);
onClickOutside(container, () => show.value = false);

const debouncedSearch = debouncedRef(search, 500);
watch(debouncedSearch, (value) => {
    emits('change', value);
});

const select = (value, label) => {
    model.value = value;
    selected.value = label;
    show.value = false;
}

const clear = () => {
    model.value = null;
    selected.value = '';
}

onUpdated(() => {
    input.value?.focus();
});
</script>

<template>
    <div ref="container" class="relative w-full">
        <div class="flex gap-1 items-center w-full border border-gray-300 py-1 px-3 rounded-md shadow-sm">
            <input class="border-0 w-full focus:ring-0 focus:outline-0 h-8 p-0 cursor-default" :placeholder="placeholder" readonly @focus="show = true" :value="selected" />
            <small class="text-lg text-gray-400 cursor-pointer" @click="clear">&times;</small>
        </div>
        <div v-show="show" class="absolute bg-white shadow-xl rounded mt-1 border border-gray-100 w-full">
            <TextInput ref="input" v-model="search" :id="id" class="w-[96%] h-8 text-gray-700 m-3" autocomplete="off" autofocus />
            <ul class="max-h-40 overflow-y-auto empty:hidden">
                <template v-if="show">
                    <li
                        v-for="option in options"
                        :key="option.value"
                        class="capitalize cursor-pointer hover:bg-secondary p-2 border-b border-gray-100 truncate text-xs last:border-0"
                        :class="{ 'bg-secondary': option.value === model }"
                        @click="() => select(option.value, option.label)"
                        >
                        {{ option.label }}
                    </li>
                </template>
                <li class="text-muted text-center p-3 border-b border-gray-100 text-xs" v-show="!options.length && search">No results found</li>
            </ul>
        </div>
    </div>
</template>
