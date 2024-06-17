<script setup>
import { Head, Link } from '@inertiajs/vue3';
import manage from './../../images/manage.svg';
import progress from './../../images/progress.svg';
import completed from './../../images/completed.svg';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="Welcome" />

    <div class="p-5 flex flex-col md:max-w-7xl m-auto">
        <nav v-if="canLogin" class="flex flex-1 justify-end">
            <Link
                v-if="$page.props.auth.user"
                :href="route('dashboard')"
                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]"
            >
                Dashboard
            </Link>

            <template v-else>
                <Link
                    :href="route('login')"
                    class="rounded-md px-3 py-2 text-primary ring-1 ring-transparent transition hover:text-primary/70 focus:outline-none focus-visible:ring-[#FF2D20] font-bold"
                >
                    Log in
                </Link>

                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="rounded-md px-3 py-2 text-primary ring-1 ring-transparent transition hover:text-primary/70 focus:outline-none focus-visible:ring-[#FF2D20] font-bold"
                >
                    Register
                </Link>
            </template>
        </nav>

        <div class="flex gap-10 items-center justify-between min-h-64">
            <img :src="manage" alt="Manage Task" class="w-1/2" />
            <div class="flex flex-col gap-3">
                <h1 class="text-3xl md:text-6xl font-bold text-primary">Manage</h1>
                <p class="text-lg md:text-2xl text-gray-600">With Sprintly, you can easily manage your tasks</p>
            </div>
        </div>

        <div class="flex gap-10 items-center justify-between min-h-64">
            <div class="flex flex-col gap-3">
                <h1 class="text-3xl md:text-6xl font-bold text-primary">Progress</h1>
                <p class="text-lg md:text-2xl text-gray-600">Easily keep track of your progress</p>
            </div>
            <img :src="progress" alt="Manage Task" class="w-1/2" />
        </div>

        <div class="flex gap-10 items-center justify-between min-h-64">
            <img :src="completed" alt="Manage Task" class="w-1/2" />
            <div class="flex flex-col gap-3">
                <h1 class="text-3xl md:text-6xl font-bold text-primary">Complete</h1>
                <p class="text-lg md:text-2xl text-gray-600">Celebrate what you have accomplished</p>
            </div>
        </div>
    </div>
</template>
