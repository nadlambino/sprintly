<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Metrics from '@/Components/Dashboard/Metrics.vue';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="p-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-center flex flex-col md:flex-row gap-5">
                <Suspense>
                    <Metrics />
                </Suspense>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
