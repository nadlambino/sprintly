<script setup>
import { computed, ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useUrlSearchParams } from '@vueuse/core';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import RadioInput from '@/Components/RadioInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Alert from '@/Components/Alert.vue';
import InputError from '@/Components/InputError.vue';
import Toggle from '@/Components/Toggle.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SubMenu from '@/Components/Tasks/SubMenu.vue';

const props = defineProps({
    statuses: Array,
    task: {
        type: Object,
        default: () => ({})
    }
});

const url = useUrlSearchParams('history');

const form = useForm({
    id: props.task?.id || null,
    title: props.task?.title || '',
    content: props.task?.content || '',
    images: [],
    status_id: props.task?.status_id || props.statuses?.find(s => s.name === url.status)?.id || props.statuses[0].id,
    publish: props.task?.published_at ? true : false,
    replace_images: false
});

const success = ref(false);
const message = ref(null);
const errors = ref({});

const images = computed(() => Array.from(form.images).map((image) => URL.createObjectURL(image)));
const savedImages = computed(() => props.task?.images?.map((image) => window.location.origin + '/' + image.path));
const hasServerError = computed(() => success.value === false && errors?.value?.length === 0 && message?.value !== null);
const isNew = computed(() => props.task?.id === undefined);

const errorHasImageServerError = computed(() => {
    return Object.keys(errors.value).some(key => key.toLowerCase().includes('images.'));
});

const submit = () => {
    success.value = false;
    errors.value = {};
    const request = isNew.value ? create : update;

    request().then(({ data }) => {
        success.value = true;
        message.value = data.message;

        if (isNew.value) form.reset();
    }).catch((error) => {
        success.value = false;
        message.value = error?.response?.message || error?.response?.data?.message;
        errors.value = error?.response?.errors || error?.response?.data?.errors;
    });
}

const create = () => {
    const data = form.data();
    delete data.replace_images;

    return window.axios.post(route('api.tasks.store'), data, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    });
}

const update = () => {
    const data = form.data();
    data._method = 'PUT';

    return window.axios.post(route('api.tasks.update', { task: form.id }), data, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    });
}
</script>

<template>
    <Head title="Tasks" />

    <AuthenticatedLayout>
        <template #header>
            <SubMenu :create="false" :filters="false"/>
        </template>
        <Alert :show="success || hasServerError || errorHasImageServerError" :message="message" title="Task">
            <template #actions>
                <Link :href="route('tasks.index')">
                    <PrimaryButton class="flex gap-1 justify-center items-center">
                        Go Back To Tasks
                    </PrimaryButton>
                </Link>
            </template>
        </Alert>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form class="flex flex-col gap-5 p-5 max-w-2xl m-auto" @submit.prevent enctype="multipart/form-data">
                        <div class="flex flex-col gap-2">
                            <InputLabel for="title" value="Title" required />
                            <TextInput
                                id="title"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.title"
                                placeholder="Task Summary..."
                                minlength="3"
                                maxlength="100"
                                required
                            />
                            <InputError v-for="error in errors?.title || []" :message="error" />
                        </div>

                        <div class="flex flex-col gap-2">
                            <InputLabel for="content" value="Content" required />
                            <TextInput
                                id="content"
                                type="textarea"
                                class="mt-1 block w-full"
                                v-model="form.content"
                                minlength="3"
                                maxlength="10000"
                                required
                            />
                            <InputError v-for="error in errors?.content || []" :message="error" />
                        </div>

                        <div class="flex flex-col gap-2">
                            <InputLabel for="status" value="Status" required />
                            <div class="flex gap-10">
                                <div v-for="status in statuses" class="flex gap-2 items-center">
                                    <RadioInput :key="status.id" :value="status.id" v-model="form.status_id" name="status" />
                                    <InputLabel :for="status.id" :value="status.name" class="capitalize cursor-pointer"/>
                                </div>
                            </div>
                            <InputError v-for="error in errors?.status_id || []" :message="error" />
                        </div>

                        <div class="flex flex-col gap-2">
                            <InputLabel for="publish" value="Publish" />
                            <Toggle id="publish" v-model="form.publish"/>
                        </div>

                        <div class="flex flex-col gap-2">
                            <InputLabel for="images" value="Image(s)" />
                            <input type="file" class="focus:ring-0 focus:outline-none"ame="images" accept="image/*" multiple @input="form.images = $event.target.files" />
                            <InputError v-for="error in errors?.images || []" :message="error" />

                            <template v-if="!isNew">
                                <InputLabel for="replaced-images" value="Replace Image(s)" :class="{ 'text-muted cursor-not-allowed': images.length === 0 }" />
                                <Toggle id="replaced-images" v-model="form.replace_images" :disabled="images.length === 0" />
                            </template>

                            <div class="flex gap-5 justify-center flex-wrap mt-3">
                                <img v-for="image in images" alt="" :src="image" class="aspect-square w-28 object-cover shadow-md rounded-md border" />
                                <template v-if="!form.replace_images">
                                    <img v-for="image in savedImages" alt="" :src="image" class="aspect-square w-28 object-cover shadow-md rounded-md border" />
                                </template>
                            </div>
                        </div>

                        <div class="flex justify-end items-center gap-3 mt-2">
                            <Link :href="route('tasks.index')">
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
