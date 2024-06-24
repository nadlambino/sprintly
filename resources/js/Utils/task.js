import { useUrlSearchParams } from '@vueuse/core';
import { defineStore } from 'pinia';
import { ref, watch, computed, reactive } from 'vue';
import { useInfiniteQuery } from '@tanstack/vue-query';
import { useDebounce } from '@vueuse/core';
import { useStatusStore } from './status';

export const useTaskStore = defineStore('tasks', () => {
    const url = useUrlSearchParams('history');
    const search = ref(url.title || '');
    const sortBy = ref(url.sort || 'created_at');
    const perPage = ref(url.per_page || 5);
    const status = ref(url.status || 'all');

    watch(search, () => {
        url.title = search.value || null;
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
        status,
    };
});


export function useTaskApi(params = {}) {
    const taskStore = useTaskStore();
    const { getStatus } = useStatusStore();

    const statusId = ref(params?.status_id);
    const sort  = ref(params?.sort || 'created_at');
    const published = ref(params?.published);
    const trashed = ref(params?.trashed);
    const page = ref(params?.page || 1);
    const perPage = ref(params?.per_page || taskStore.perPage);
    const search = ref(params?.search);
    const searchDebounce = useDebounce(search, 500);
    const hasNextPage = ref(false);
    const total = ref(0);

    watch(() => taskStore.search, () => {
        search.value = taskStore.search;
        page.value = 1;
    });

    watch(() => taskStore.perPage, () => {
        perPage.value = taskStore.perPage;
        page.value = 1;
    });

    watch(() => taskStore.sortBy, () => {
        sort.value = taskStore.sortBy;
        page.value = 1;
    });

    watch(() => taskStore.status, () => {
        statusId.value = getStatus({ name: taskStore.status })?.id;
        page.value = 1;
    });

    const get = async ({ pageParam = 1 }) => {
        const response = await window.axios.get(route('api.tasks.index', {
            filter: {
                search: searchDebounce.value,
                status_id: statusId.value,
                published: published.value,
                trashed: trashed.value,
                parent_id: params?.parent_id
            },
            sort: sort.value,
            include: 'status,images,parent,children,priorityLevel',
            page: pageParam,
            per_page: perPage.value
        }));

        hasNextPage.value = response?.data?.metadata?.has_next_page;
        page.value = response?.data?.metadata?.next_page;
        total.value = response?.data?.metadata?.total;

        return response?.data?.data || [];
    }

    const { data, isPending, isFetching, isFetchingNextPage, refetch, fetchNextPage } = useInfiniteQuery({
        queryKey: [{ statusId, sort, published, trashed, searchDebounce, perPage }],
        queryFn: get,
        initialPageParam: 1,
        getNextPageParam: () => hasNextPage.value ? page.value : null,
        enabled: false
    });

    const isRequesting = computed(() => isPending.value || isFetching.value || isFetchingNextPage.value);
    const isEmpty = computed(() => !isRequesting.value && data?.value?.pages[0]?.length === 0);

    watch([searchDebounce, statusId, sort, perPage], refetch);

    const next = () => {
        if (hasNextPage.value) fetchNextPage();
    }

    const getMetrics = () => {
        return window.axios.get(route('api.tasks.metrics'));
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
     * Request to progress a task
     *
     * @param {string|number} id
     * @param {object} headers
     * @returns {Promise}
     */
    const progress = (id, headers) => {
        return window.axios
            .put(route('api.tasks.progress', { task: id }), {}, { headers });
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

    /**
     * Request to get task that are valid to be parent of a task
     *
     * @param {Object|FormData} filter
     * @returns {Promise}
     */
    const getParents = (filter) => {
        return window.axios
            .get(route('api.tasks.parents', {
                filter,
                per_page: perPage.value
            }));
    }

    return {
        data,
        total,
        isRequesting,
        isEmpty,
        hasNextPage,
        refetch,
        next,
        getMetrics,
        create,
        update,
        progress,
        destroy,
        restore,
        getParents
    };
}

/**
 * Generate random keys for each status that can be used in kanban column components
 * This is use when a task is move from one status to another, then we update the
 * keys of the two affected columns to force re-rendering to reflect data changes.
 *
 * @returns {Array<Object, Function>}
 */
export function useKanbanColumnKeys() {
    const { statuses } = useTaskStore();

    // Generate random keys for each status
    // Returned format is: { [status.id]: Math.random()] }
    const statusesKeys = collect(statuses)
        .map((status) => ({ name: status.id, value: Math.random() }))
        .pluck('value', 'name').all();

    const keys = reactive(statusesKeys);

    const update = (keyNames) => {
        keyNames.forEach((key) => {
            keys[key] = Math.random();
        });
    }

    return [
        keys,
        update
    ]
}

export function useTimeSpent() {
    const format = (timespent) => {
        if (! timespent) return '';

        timespent = parseFloat(timespent);
        if (timespent < 1) {
            const minutes = (Math.abs(timespent) * 60).toFixed(0);
            const unit = minutes > 1 ? 'minutes' : 'minute';

            return `${minutes} ${unit}`;
        }

        const hours = timespent;
        const unit = hours > 1 ? 'hours' : 'hour';

        return `${hours} ${unit}`;
    }

    return format;
}
