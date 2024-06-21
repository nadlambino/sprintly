<script setup>
import { computed, ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { usePriorityLevelApi } from '@/Utils/priority-level';
import { ColorPicker } from "vue3-colorpicker";
import 'vue3-colorpicker/style.css';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/Shared/InputLabel.vue';
import TextInput from '@/Components/Shared/TextInput.vue';
import PrimaryButton from '@/Components/Shared/PrimaryButton.vue';
import Alert from '@/Components/Shared/Alert.vue';
import InputError from '@/Components/Shared/InputError.vue';
import SecondaryButton from '@/Components/Shared/SecondaryButton.vue';
import SubMenu from '@/Components/Statuses/SubMenu.vue';

const props = defineProps({
    priorityLevel: {
        type: Object,
        default: () => ({})
    }
});

const { create, update } = usePriorityLevelApi();

const isNew = computed(() => props.priorityLevel?.id === undefined);

const form = useForm({
    id: props.priorityLevel?.id || null,
    name: props.priorityLevel?.name || '',
    description: props.priorityLevel?.description || '',
    color: props.priorityLevel?.color || '#000000',
    score: props.priorityLevel?.score || 0
});

const success = ref(false);
const message = ref(null);
const errors = ref({});

const hasServerError = computed(() => success.value === false && errors?.value?.length === 0 && message?.value !== null);

const submit = async () => {
    success.value = false;
    errors.value = {};
    const data = form.data();

    if (isNew.value) {
        delete data.id;

        return await create(data)
            .then(then)
            .catch(error);
    }

    return await update(data.id, data)
        .then(then)
        .catch(error);
}

const then = ({ data }) => {
    success.value = true;
    message.value = data.message;

    if (isNew.value) form.reset();
}

const error = (error) => {
    success.value = false;
    message.value = error?.response?.message || error?.response?.data?.message;
    errors.value = error?.response?.errors || error?.response?.data?.errors;
}
</script>

<template>
    <Head title="Priority Levels" />

    <AuthenticatedLayout>
        <template #header>
            <SubMenu trashed/>
        </template>
        <Alert :show="success || hasServerError" :message="message" title="Priority Level">
            <template #actions>
                <Link :href="route('priority-levels.index')">
                    <PrimaryButton class="flex gap-1 justify-center items-center">
                        Go Back To Priority Level
                    </PrimaryButton>
                </Link>
            </template>
        </Alert>

        <div class="p-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <form class="flex flex-col gap-5 p-5 max-w-2xl m-auto" @submit.prevent>
                        <div class="flex flex-col gap-2">
                            <InputLabel for="name" value="Name" required />
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                minlength="3"
                                maxlength="50"
                                required
                            />
                            <InputError v-for="error in errors?.name || []" :message="error" />
                        </div>

                        <div class="flex flex-col gap-2">
                            <InputLabel for="description" value="Description" />
                            <TextInput
                                id="description"
                                type="textarea"
                                class="mt-1 block w-full"
                                v-model="form.description"
                                maxlength="10000"
                            />
                            <InputError v-for="error in errors?.description || []" :message="error" />
                        </div>

                        <div class="flex flex-col gap-2">
                            <InputLabel for="score" value="Score" />
                            <input
                                id="score"
                                type="range"
                                class="mt-1 block w-full"
                                v-model="form.score"
                                min="1"
                                max="5"
                                step="1"
                                required
                            />
                            <InputError v-for="error in errors?.score || []" :message="error" />
                        </div>

                        <div class="flex flex-col gap-2">
                            <InputLabel for="color" value="Color" />
                            <ColorPicker id="color" v-model:pureColor="form.color" format="hex8" />
                            <InputError v-for="error in errors?.color || []" :message="error" />
                        </div>

                        <div class="flex justify-end items-center gap-3 mt-2">
                            <Link :href="route('statuses.index')">
                                <SecondaryButton>Cancel</SecondaryButton>
                            </Link>
                            <PrimaryButton class="w-24 flex justify-center" @click="() => submit(false)" :disabled="form.processing">{{ isNew ? 'Create' : 'Update' }}</PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
:deep(.vc-color-wrap) {
    width: 100% !important;
    height: 3rem !important;
    @apply rounded-md shadow-sm;
}
</style>
