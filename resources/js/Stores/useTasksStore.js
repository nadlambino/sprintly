import { useUrlSearchParams } from '@vueuse/core';
import { defineStore } from 'pinia';
import { ref, watch } from 'vue';

export const useTasksStore = defineStore('tasks', () => {
    const url = useUrlSearchParams('history');
    const search = ref(url.title || '');
    const sortBy = ref(url.sort || 'created_at');
    const perPage = ref(url.per_page || 10);

    watch(search, () => {
        url.title = search.value;
    });

    watch(sortBy, () => {
        url.sort = sortBy.value;
    });

    watch(perPage, () => {
        url.per_page = perPage.value;
    });

    return {
        search,
        sortBy,
        perPage
    };
});
