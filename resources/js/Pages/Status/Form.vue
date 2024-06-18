<script setup>
import { computed, ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useStatusApi } from '@/Utils/status';
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
    status: {
        type: Object,
        default: () => ({})
    }
});

const { create, update } = useStatusApi();

const isNew = computed(() => props.status?.id === undefined);

const form = useForm({
    id: props.status?.id || null,
    name: props.status?.name || '',
    description: props.status?.description || '',
    color: props.status?.color || '#000000',
    order: props.status?.order || 0
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
    <Head title="Tasks" />

    <AuthenticatedLayout>
        <template #header>
            <SubMenu trashed/>
        </template>
        <Alert :show="success || hasServerError" :message="message" title="Status">
            <template #actions>
                <Link :href="route('statuses.index')">
                    <PrimaryButton class="flex gap-1 justify-center items-center">
                        Go Back To Status
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
