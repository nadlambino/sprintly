import { computed, ref, watch } from 'vue';
import { useQuery } from '@tanstack/vue-query';
import { defineStore } from 'pinia';
import { useDebounce, useUrlSearchParams } from '@vueuse/core';
import collect from 'collect.js';

export const useStatusStore = defineStore('statuses', () => {
    const url = useUrlSearchParams('history');
    const statuses = ref([]);
    const search = ref(url.search || '');
    const sortBy = ref(url.sort || 'order');

    watch(search, () => {
        url.search = search.value || null;
    });

    const getStatus = (filters) => {
        return collect(statuses.value).first((status) => Object.entries(filters).every(([key, value]) => status[key] == value));
    }

    const setStatuses = (data) => statuses.value = data;

    return {
        statuses,
        getStatus,
        setStatuses,
        search,
        sortBy
    };
});

export function useStatusApi(params = {}) {
    const statusStore = useStatusStore();
    const search = ref(params?.search || statusStore.search);
    const searchDebounce = useDebounce(search, 500);
    const sortBy = ref(params?.sort || statusStore.sortBy);

    watch(() => statusStore.search, () => {
        search.value = statusStore.search;
    });

    watch(() => statusStore.sortBy, () => {
        sortBy.value = statusStore.sortBy;
    });

    const get = async () => {
        const response = await window.axios.get(route('api.statuses.index', {
            filter: {
                search: searchDebounce.value,
                trashed: params?.trashed,
            },
            sort: sortBy.value,
        }));

        return response?.data?.data || [];
    }

    const { data, isPending, isFetching, refetch } = useQuery({
        queryKey: ['statuses', { searchDebounce, sortBy }],
        queryFn: get,
    });

    const isRequesting = computed(() => isPending.value || isFetching.value);
    const isEmpty = computed(() => !isRequesting.value && data?.value?.length === 0);

    /**
     * Request to create a status
     *
     * @param {object|FormData} data
     * @param {object} headers
     * @returns {Promise}
     */
    const create = (data, headers) => {
        return window.axios.post(route('api.statuses.store'), data, { headers });
    }

    /**
     * Request to update a status
     *
     * @param {string|number} id
     * @param {object|FormData} data
     * @param {object} headers
     * @returns {Promise}
     */
    const update = (id, data, headers) => {
        return window.axios
            .post(route('api.statuses.update', { status: id }), { ...data, _method: 'PUT' }, { headers });
    }

    /**
     * Request to destroy a status
     *
     * @param {string|number} id
     * @returns {Promise}
     */
    const destroy = (id) => {
        return window.axios
            .delete(route('api.statuses.destroy', { status: id }));
    }

    /**
     * Request to restore a status
     *
     * @param {string|number} id
     * @returns {Promise}
     */
    const restore = (id) => {
        return window.axios
            .put(route('api.statuses.restore', { status: id }));
    }

    const sort = (id, data, headers) => {
        return window.axios
            .post(route('api.statuses.sort', { status: id }), { ...data, _method: 'PUT' }, { headers });
    }

    return {
        data,
        isRequesting,
        isEmpty,
        refetch,
        create,
        update,
        destroy,
        restore,
        sort
    };
}

export function useStatusSort() {
    const { sort: sortStatus } = useStatusApi();

    const sort = (id, oldIndex, newIndex) => {
        if (oldIndex == newIndex) return (new Promise(() => {}));

        return sortStatus(id, { new_order: newIndex + 1, old_order: oldIndex + 1 });
    }

    return sort;
}
