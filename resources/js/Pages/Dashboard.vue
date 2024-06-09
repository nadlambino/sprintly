<script setup>
import { Head } from '@inertiajs/vue3';
import TodoCount from '@/Components/Dashboard/TodoCount.vue';
import InProgressCount from '@/Components/Dashboard/InProgressCount.vue';
import DoneCount from '@/Components/Dashboard/DoneCount.vue';
import DraftCount from '@/Components/Dashboard/DraftCount.vue';
import TrashedCount from '@/Components/Dashboard/TrashedCount.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
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
                    <TodoCount />
                </Suspense>
                <Suspense>
                    <InProgressCount />
                </Suspense>
                <Suspense>
                    <DoneCount />
                </Suspense>
                <Suspense>
                    <DraftCount />
                </Suspense>
                <Suspense>
                    <TrashedCount />
                </Suspense>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
