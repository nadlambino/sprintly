import { useInfiniteQuery } from '@tanstack/vue-query';
import { ref, watchEffect } from 'vue';
import { useDebounce } from "@vueuse/core";
import { useTasksStore } from '@/Stores/useTasksStore';

export function useTasks(params = {}) {
    const tasksStore = useTasksStore();

    const status = ref(params?.status);
    const sort  = ref(params?.sort || 'created_at');
    const published = ref(params?.published || false);
    const page = ref(params?.page || 1);
    const perPage = ref(params?.per_page || 10);
    const search = ref(params?.search);
    const searchDebounce = useDebounce(search, 500);

    watchEffect(() => {
        search.value = tasksStore.search;
        sort.value = tasksStore.sortBy;
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

        page.value = response?.data?.metadata?.next_page;

        return response?.data?.data || [];
    }

    const { isPending, isFetching, isFetchingNextPage, isError, data, error, refetch, hasNextPage, fetchNextPage } = useInfiniteQuery({
        queryKey: [{ status, sort, published, searchDebounce, perPage }],
        queryFn: get,
        getNextPageParam: () => page.value
    });

    return {
        status,
        sort,
        data,
        isPending,
        isFetching,
        isError,
        error,
        refetch,
        fetchNextPage,
        isFetchingNextPage,
        hasNextPage
    };
}
