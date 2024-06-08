import { useUrlSearchParams } from '@vueuse/core';
import { defineStore } from 'pinia';
import { ref, watch } from 'vue';

export const useTasksStore = defineStore('tasks', () => {
    const url = useUrlSearchParams('history');
    const search = ref(url.title || '');
    const sortBy = ref(url.sort || 'created_at');

    watch(search, () => {
        url.title = search.value;
    });

    watch(sortBy, () => {
        url.sort = sortBy.value;
    });

    return {
        search,
        sortBy
    };
});
