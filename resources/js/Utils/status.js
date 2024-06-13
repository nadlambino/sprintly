import { computed, ref, watch } from 'vue';
import { useQuery } from '@tanstack/vue-query';
import { defineStore } from 'pinia';
import { useUrlSearchParams } from '@vueuse/core';

export const useStatusStore = defineStore('statuses', () => {
    const url = useUrlSearchParams('history');
    const statuses = ref([]);
    const status = ref(url.status);
    const color = ref(url.color);

    watch(status, () => {
        url.status = status.value;
    });

    watch(color, () => {
        url.color = color.value;
    });

    const getStatus = (filters) => {
        return statuses.value.find(status => Object.entries(filters).every(([key, value]) => status[key] === value));
    }

    const setStatuses = (data) => statuses.value = data;

    return {
        statuses,
        getStatus,
        setStatuses,
        status,
        color
    };
});

export function useStatusApi(params = {}) {
    const statusStore = useStatusStore();
    const status = ref(params?.status || statusStore.status);
    const color = ref(params?.color || statusStore.color);

    const get = async () => {
        const response = await window.axios.get(route('api.statuses.index', {
            filter: {
                status: status.value,
                color: color.value
            }
        }));

        return response?.data?.data || [];
    }

    const { data, isPending, isFetching, refetch } = useQuery({
        queryKey: ['statuses'],
        queryFn: get,
    });

    const isRequesting = computed(() => isPending.value || isFetching.value);
    const isEmpty = computed(() => !isRequesting.value && data?.value?.length === 0);

    /**
     * Request to create a task
     *
     * @param {object|FormData} data
     * @param {object} headers
     * @returns {Promise}
     */
    const create = (data, headers) => {
        return window.axios.post(route('api.statuses.store'), data, { headers });
    }

    /**
     * Request to update a task
     *
     * @param {string|number} id
     * @param {object|FormData} data
     * @param {object} headers
     * @returns {Promise}
     */
    const update = (id, data, headers) => {
        return window.axios
            .post(route('api.statuses.update', { task: id }), { ...data, _method: 'PUT' }, { headers });
    }

    /**
     * Request to destroy a task
     *
     * @param {string|number} id
     * @returns {Promise}
     */
    const destroy = (id) => {
        return window.axios
            .delete(route('api.statuses.destroy', { task: id }));
    }

    /**
     * Request to restore a task
     *
     * @param {string|number} id
     * @returns {Promise}
     */
    const restore = (id) => {
        return window.axios
            .put(route('api.statuses.restore', { task: id }));
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
    };
}
