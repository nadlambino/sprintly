<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import RadioInput from '@/Components/RadioInput.vue';
import NavLink from '@/Components/NavLink.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Alert from '@/Components/Alert.vue';
import axios from 'axios';
import { ref } from 'vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    statuses: Array
});

const form = useForm({
    title: '',
    description: '',
    images: [],
    status_id: props.statuses[0].id,
    published: 0
});

const success = ref(false);
const message = ref(null);
const errors = ref(null);

const submit = (draft) => {
    success.value = false;
    errors.value = null;

    const routeName = draft ? 'api.tasks.draft' : 'api.tasks.store';

    axios.post(route(routeName), form.data(), {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }).then(({ data }) => {
        success.value = true;
        message.value = data.message;
    }).catch((error) => {
        success.value = false;
        message.value = error?.response?.message || error?.response?.data?.message;
        errors.value = error?.response?.errors || error?.response?.data?.errors;
    });
}
</script>

<template>
    <Head title="Tasks" />

    <AuthenticatedLayout>
        <Alert :show="success" :message="message">
            <template #actions>
                <NavLink :href="route('tasks.index')" unstyled>
                    <PrimaryButton class="flex gap-1 justify-center items-center">
                        Go Back To Tasks
                    </PrimaryButton>
                </NavLink>
            </template>
        </Alert>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form class="flex flex-col gap-5 p-5" @submit.prevent enctype="multipart/form-data">
                        <div class="flex flex-col gap-2">
                            <InputLabel for="title" value="Title" />
                            <TextInput id="title" type="text" class="mt-1 block w-full" v-model="form.title" />
                            <InputError v-for="error in errors?.title || []" :message="error" />
                        </div>

                        <div class="flex flex-col gap-2">
                            <InputLabel for="description" value="Description" />
                            <TextInput
                                id="description"
                                type="textarea"
                                class="mt-1 block w-full"
                                v-model="form.description"
                            />
                            <InputError v-for="error in errors?.description || []" :message="error" />
                        </div>

                        <div class="flex flex-col gap-2">
                            <InputLabel for="status" value="Status" />
                            <div class="flex gap-10">
                                <div v-for="status in statuses" class="flex gap-2 items-center">
                                    <RadioInput :key="status.id" :value="status.id" v-model="form.status_id" name="status" />
                                    <InputLabel :for="status.id" :value="status.name" class="capitalize cursor-pointer"/>
                                </div>
                            </div>
                            <InputError v-for="error in errors?.status_id || []" :message="error" />
                        </div>

                        <div class="flex flex-col gap-2">
                            <InputLabel for="images" value="Image(s)" />
                            <input type="file" name="images" accept="image/*" multiple @input="form.images = $event.target.files" />
                            <InputError v-for="error in errors?.images || []" :message="error" />
                        </div>

                        <div class="flex justify-end items-center gap-3">
                            <NavLink class="text-muted w-24 flex justify-center hover:bg-secondary py-1 rounded-md hover:border-secondary hover:border" unstyled :href="route('tasks.index')">Cancel</NavLink>
                            <SecondaryButton class="w-24 flex justify-center" @click="() => submit(true)">Draft</SecondaryButton>
                            <PrimaryButton class="w-24 flex justify-center" @click="() => submit(false)">Create</PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
