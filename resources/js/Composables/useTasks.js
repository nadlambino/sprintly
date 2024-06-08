import { useQuery } from '@tanstack/vue-query';
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
    const hasNextPage = ref(false);

    watchEffect(() => {
        search.value = tasksStore.search;
        sort.value = tasksStore.sortBy;
    });

    const get = async () => {
        const response = await axios.get(route('api.tasks.index', {
            filter: {
                title: searchDebounce.value,
                status: status.value,
                published: published.value
            },
            sort: sort.value,
            include: 'status,images',
            page: page.value,
            per_page: perPage.value
        }));

        hasNextPage.value = response?.data?.metadata?.has_next_page;

        return response?.data?.data || [];
    }

    const { isPending, isFetching, isError, data, error, refetch } = useQuery({
        queryKey: [{ status, sort, published, searchDebounce, page, perPage }],
        queryFn: get,
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
        hasNextPage
    };
}
