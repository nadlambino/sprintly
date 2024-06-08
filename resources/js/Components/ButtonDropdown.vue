<script setup>
import RadioInput from '@/Components/RadioInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { ref } from 'vue';
import { onClickOutside } from '@vueuse/core'
import SecondaryButton from './SecondaryButton.vue';

const props = defineProps({
    label: String,
    options: Array
});

const selected = defineModel();
const showSort = ref(false);
const target = ref(null)

onClickOutside(target, event => showSort.value = false);
</script>

<template>
    <div ref="target" class="relative">
        <SecondaryButton class="h-10" @click="showSort = !showSort" id="dropdownRadioButton" data-dropdown-toggle="dropdownDefaultRadio">
            {{ label }}
            <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
            </svg>
        </SecondaryButton>

        <!-- Dropdown menu -->
        <div v-show="showSort" id="dropdownDefaultRadio" class="z-10 w-48 bg-white divide-y divide-gray-100 rounded-lg shadow absolute right-0 top-12">
            <ul class="p-3 space-y-3 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownRadioButton">
                <li v-for="option in options" :key="option.value">
                    <div class="flex gap-2 items-center">
                        <RadioInput :id="option.value" :value="option.value" v-model="selected" name="sort" />
                        <InputLabel :for="option.value" :value="option.label" class="capitalize cursor-pointer"/>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
