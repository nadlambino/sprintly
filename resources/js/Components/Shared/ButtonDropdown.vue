<script setup>
import { ref, computed } from 'vue';
import { onClickOutside } from '@vueuse/core';
import RadioInput from '@/Components/Shared/RadioInput.vue';
import InputLabel from '@/Components/Shared/InputLabel.vue';

const props = defineProps({
    label: String,
    options: Array,
    placement: {
        type: String,
        default: 'right-0'
    }
});

const selected = defineModel();
const showSort = ref(false);
const target = ref(null)
const text = computed(() => props.options.find(option => option.value === selected.value)?.label);

onClickOutside(target, () => showSort.value = false);
</script>

<template>
    <div ref="target" class="relative">
        <button class="focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 bg-secondary text-[10px] rounded-md flex justify-between items-center px-2 font-bold text-gray-700 uppercase h-10" @click="showSort = !showSort" id="dropdownRadioButton" data-dropdown-toggle="dropdownDefaultRadio">
            <span v-html="label || text"></span>
            <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
            </svg>
        </button>

        <!-- Dropdown menu -->
        <div v-show="showSort" id="dropdownDefaultRadio" class="z-10 w-48 bg-white divide-y divide-gray-100 rounded-lg shadow-xl absolute top-12" :class="placement">
            <ul class="py-1 text-xs" aria-labelledby="dropdownRadioButton">
                <li v-for="option in options" :key="option.value">
                    <div class="flex items-center">
                        <RadioInput class="hidden" :id="option.value" :value="option.value" v-model="selected" name="sort" />
                        <InputLabel :for="option.value" :value="option.label" class="capitalize cursor-pointer w-full hover:bg-secondary p-2" :class="{ 'bg-secondary': selected === option.value }"/>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
