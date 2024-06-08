import { useQuery } from '@tanstack/vue-query';
import { ref, watch } from 'vue';
import { useDebounce } from "@vueuse/core";
import { useTasksStore } from '@/Stores/useTasksStore';

export function useTasks(params = {}) {
    const tasksStore = useTasksStore();

    const status = ref(params?.status);
    const sort  = ref(params?.sort || 'created_at');
    const draft = ref(params?.draft || false);
    const page = ref(params?.page || 1);
    const perPage = ref(params?.per_page || 10);
    const search = ref(params?.search);
    const searchDebounce = useDebounce(search, 500);

    watch(() => tasksStore.search, () => {
        search.value = tasksStore.search;
    });

    const get = async () => {
        const response = await axios.get(route('api.tasks.index', {
            filter: {
                title: searchDebounce.value,
                status: status.value,
                draft: draft.value
            },
            sort: sort.value,
            include: 'status,images',
            page: page.value,
            per_page: perPage.value
        }));

        return response?.data?.data || [];
    }

    const { isPending, isFetching, isError, data, error } = useQuery({
        queryKey: [{ status, sort, draft, searchDebounce, page, perPage }],
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
    };
}
