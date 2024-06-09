import { useInfiniteQuery } from '@tanstack/vue-query';
import { computed, ref, watchEffect } from 'vue';
import { useDebounce } from "@vueuse/core";
import { useTasksStore } from '@/Stores/useTasksStore';

export function useTasks(params = {}) {
    const tasksStore = useTasksStore();

    const status = ref(params?.status);
    const sort  = ref(params?.sort || 'created_at');
    const published = ref(params?.published || false);
    const page = ref(params?.page || 1);
    const perPage = ref(params?.per_page || tasksStore.perPage);
    const search = ref(params?.search);
    const searchDebounce = useDebounce(search, 500);
    const hasNextPage = ref(false);
    const total = ref(0);

    watchEffect(() => {
        search.value = tasksStore.search;
        sort.value = tasksStore.sortBy;
        perPage.value = tasksStore.perPage;
    });

    const get = async ({ pageParam = 1 }) => {
        const response = await axios.get(route('api.tasks.index', {
            filter: {
                title: searchDebounce.value,
                status: status.value,
                published: published.value
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
        queryKey: [{ status, sort, published, searchDebounce, perPage }],
        queryFn: get,
        initialPageParam: 1,
        getNextPageParam: () => hasNextPage.value ? page.value : null,
    });

    const isRequesting = computed(() => isPending.value || isFetching.value || isFetchingNextPage.value);
    const isEmpty = computed(() => !isRequesting.value && data?.value?.pages[0]?.length === 0);

    const next = () => {
        if (hasNextPage.value) fetchNextPage();
    }

    return {
        data,
        total,
        isRequesting,
        isEmpty,
        hasNextPage,
        refetch,
        next,
    };
}
