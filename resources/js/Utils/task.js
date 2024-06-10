import { useUrlSearchParams } from '@vueuse/core';
import { defineStore } from 'pinia';
import { ref, watch, computed, watchEffect } from 'vue';
import { useInfiniteQuery } from '@tanstack/vue-query';
import { useDebounce } from '@vueuse/core';

export const useTaskStore = defineStore('tasks', () => {
    const url = useUrlSearchParams('history');
    const search = ref(url.title || '');
    const sortBy = ref(url.sort || 'created_at');
    const perPage = ref(url.per_page || 10);
    const status = ref(url.status);

    watch(search, () => {
        url.title = search.value;
    });

    watch(sortBy, () => {
        url.sort = sortBy.value;
    });

    watch(perPage, () => {
        url.per_page = perPage.value;
    });

    watch(status, () => {
        url.status = status.value;
    });

    return {
        search,
        sortBy,
        perPage,
        status
    };
});


export function useTaskApi(params = {}) {
    const taskStore = useTaskStore();

    const status = ref(params?.status || taskStore.status);
    const sort  = ref(params?.sort || 'created_at');
    const published = ref(params?.published);
    const trashed = ref(params?.trashed);
    const page = ref(params?.page || 1);
    const perPage = ref(params?.per_page || taskStore.perPage);
    const search = ref(params?.search);
    const searchDebounce = useDebounce(search, 500);
    const hasNextPage = ref(false);
    const total = ref(0);

    watchEffect(() => {
        search.value = taskStore.search;
        sort.value = taskStore.sortBy;
        perPage.value = taskStore.perPage;
    });

    watch(() => taskStore.search, () => {
        page.value = 1;
    });

    watch(() => taskStore.status, () => {
        status.value = taskStore.status;
    });

    const get = async ({ pageParam = 1 }) => {
        const response = await window.axios.get(route('api.tasks.index', {
            filter: {
                title: searchDebounce.value,
                status: status.value,
                published: published.value,
                trashed: trashed.value,
            },
            sort: sort.value,
            include: 'status,images',
            page: pageParam,
            per_page: perPage.value
        }));

        hasNextPage.value = response?.data?.metadata?.has_next_page;
        page.value = response?.data?.metadata?.next_page;
        total.value = response?.data?.metadata?.total;

        return response?.data?.data || [];
    }

    const { data, isPending, isFetching, isFetchingNextPage, refetch, fetchNextPage } = useInfiniteQuery({
        queryKey: [{ status, sort, published, trashed, searchDebounce, perPage }],
        queryFn: get,
        initialPageParam: 1,
        getNextPageParam: () => hasNextPage.value ? page.value : null,
    });

    const isRequesting = computed(() => isPending.value || isFetching.value || isFetchingNextPage.value);
    const isEmpty = computed(() => !isRequesting.value && data?.value?.pages[0]?.length === 0);

    const next = () => {
        if (hasNextPage.value) fetchNextPage();
    }

    /**
     * Request to create a task
     *
     * @param {object|FormData} data
     * @param {object} headers
     * @returns {Promise}
     */
    const create = (data, headers) => {
        return window.axios.post(route('api.tasks.store'), data, { headers });
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
            .post(route('api.tasks.update', { task: id }), { ...data, _method: 'PUT' }, { headers });
    }

    /**
     * Request to destroy a task
     *
     * @param {string|number} id
     * @returns {Promise}
     */
    const destroy = (id) => {
        return window.axios
            .delete(route('api.tasks.destroy', { task: id }));
    }

    /**
     * Request to restore a task
     *
     * @param {string|number} id
     * @returns {Promise}
     */
    const restore = (id) => {
        return window.axios
            .put(route('api.tasks.restore', { task: id }));
    }

    return {
        data,
        total,
        isRequesting,
        isEmpty,
        hasNextPage,
        refetch,
        next,
        create,
        update,
        destroy,
        restore,
    };
}
