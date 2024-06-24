<script setup>
import { ref, watch } from 'vue';
import { debouncedRef } from '@vueuse/core';
import { onClickOutside } from '@vueuse/core';

const props = defineProps({
    options: Array,
    id: String,
    placeholder: String,
});

const model = defineModel();
const show = ref(false);
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

const select = (value, label) => {
    model.value = value;
    selected.value = label;
    show.value = false;
}

const clear = () => {
    model.value = null;
    selected.value = '';
}
</script>

<template>
    <div ref="container" class="relative w-full">
        <div class="flex gap-1 items-center w-full border border-gray-300 py-1 px-3 rounded-md shadow-sm">
            <input class="border-0 w-full focus:ring-0 focus:outline-0 h-8 p-0 cursor-default" :placeholder="placeholder" readonly @focus="show = true" :value="selected" />
            <small class="text-lg text-gray-400 cursor-pointer" @click="clear">&times;</small>
        </div>
        <div v-show="show" class="absolute bg-white shadow-xl rounded mt-1 w-full z-10 border border-gray-100">
            <ul class="max-h-40 overflow-y-auto empty:hidden">
                <template v-if="show">
                    <li
                        v-for="option in options"
                        :key="option.value"
                        class="capitalize cursor-pointer hover:bg-secondary p-2 border-b border-gray-100 last:border-b-0 truncate text-xs"
                        :class="{ 'bg-secondary': option.value === model }"
                        @click="() => select(option.value, option.label)"
                        >
                        {{ option.label }}
                    </li>
                </template>
            </ul>
        </div>
    </div>
</template>
