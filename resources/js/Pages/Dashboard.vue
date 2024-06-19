<script setup>
import { ref, onBeforeMount } from 'vue';
import { Head } from '@inertiajs/vue3';
import { useTaskApi } from '@/Utils/task';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Metrics from '@/Components/Dashboard/Metrics.vue';
import Pie from '@/Components/Dashboard/Pie.vue';

const { getMetrics } = useTaskApi();
const metrics = ref([]);
const total = ref(0);

onBeforeMount(() => {
    getMetrics().then(({ data }) => {
        total.value = data.data?.total || 0;
        metrics.value = data.data?.metrics || [];
    });
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="p-5 space-y-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Metrics :metrics="metrics" :total="total" />
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Pie :metrics="metrics" :total="total" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
