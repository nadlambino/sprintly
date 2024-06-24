import { computed, ref, watch } from 'vue';
import { useQuery } from '@tanstack/vue-query';
import { defineStore } from 'pinia';
import { useDebounce, useUrlSearchParams } from '@vueuse/core';

export const usePriorityLevelStore = defineStore('priority-level', () => {
    const url = useUrlSearchParams('history');
    const priorityLevels = ref([]);
    const search = ref(url.search || '');
    const sortBy = ref(url.sort || 'score');

    watch(search, () => {
        url.search = search.value || null;
    });

    return {
        priorityLevels,
        search,
        sortBy
    };
});

export function usePriorityLevelApi(params = {}) {
    const priorityLevelStore = usePriorityLevelStore();
    const search = ref(params?.search || priorityLevelStore.search);
    const searchDebounce = useDebounce(search, 500);
    const sortBy = ref(params?.sort || priorityLevelStore.sortBy);

    watch(() => priorityLevelStore.search, () => {
        search.value = priorityLevelStore.search;
    });

    watch(() => priorityLevelStore.sortBy, () => {
        sortBy.value = priorityLevelStore.sortBy;
    });

    const get = async () => {
        const response = await window.axios.get(route('api.priority-levels.index', {
            filter: {
                search: searchDebounce.value,
            },
            sort: sortBy.value,
        }));

        return response?.data?.data || [];
    }

    const { data, isPending, isFetching, refetch } = useQuery({
        queryKey: ['priority-levels', { searchDebounce, sortBy }],
        queryFn: get,
    });

    const isRequesting = computed(() => isPending.value || isFetching.value);
    const isEmpty = computed(() => !isRequesting.value && data?.value?.length === 0);

    /**
     * Request to create a priority-level
     *
     * @param {object|FormData} data
     * @param {object} headers
     * @returns {Promise}
     */
    const create = (data, headers) => {
        return window.axios.post(route('api.priority-levels.store'), data, { headers });
    }

    /**
     * Request to update a priority-level
     *
     * @param {string|number} id
     * @param {object|FormData} data
     * @param {object} headers
     * @returns {Promise}
     */
    const update = (id, data, headers) => {
        return window.axios
            .post(route('api.priority-levels.update', { priority_level: id }), { ...data, _method: 'PUT' }, { headers });
    }

    /**
     * Request to destroy a priority-level
     *
     * @param {string|number} id
     * @returns {Promise}
     */
    const destroy = (id) => {
        return window.axios
            .delete(route('api.priority-levels.destroy', { priority_level: id }));
    }

    return {
        data,
        isRequesting,
        isEmpty,
        refetch,
        create,
        update,
        destroy,
    };
}
