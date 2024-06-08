<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import RadioInput from '@/Components/RadioInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Alert from '@/Components/Alert.vue';
import { computed, ref } from 'vue';
import InputError from '@/Components/InputError.vue';
import Toggle from '@/Components/Toggle.vue';

const props = defineProps({
    statuses: Array,
    task: {
        type: Object,
        default: () => ({})
    }
});

const form = useForm({
    id: props.task?.id || null,
    title: props.task?.title || '',
    content: props.task?.content || '',
    images: [],
    status_id: props.task?.status_id || props.statuses[0].id,
    publish: props.task?.published_at ? true : false
});

const success = ref(false);
const message = ref(null);
const errors = ref(null);

const images = computed(() => Array.from(form.images).map((image) => URL.createObjectURL(image)));
const savedImages = computed(() => props.task?.images?.map((image) => window.location.origin + '/' + image.path));
const hasServerError = computed(() => success.value === false && errors?.value?.length === 0 && message?.value !== null);
const isNew = computed(() => props.task?.id === undefined);

const submit = () => {
    success.value = false;
    errors.value = null;
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
    return window.axios.post(route('api.tasks.store'), form.data(), {
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
        <Alert :show="success || hasServerError" :message="message" :title="isNew ? 'Task Created' : 'Task Updated'">
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

                            <div class="flex gap-5 justify-center flex-wrap mt-3">
                                <img v-for="image in images" alt="" :src="image" class="aspect-square w-28 object-cover shadow-md rounded-md border" />
                                <img v-for="image in savedImages" alt="" :src="image" class="aspect-square w-28 object-cover shadow-md rounded-md border" />
                            </div>
                        </div>

                        <div class="flex justify-end items-center gap-3">
                            <Link class="text-muted w-24 flex justify-center hover:bg-secondary py-1 rounded-md hover:border-secondary hover:border" :href="route('tasks.index')">Cancel</Link>
                            <PrimaryButton class="w-24 flex justify-center" @click="() => submit(false)">{{ isNew ? 'Create' : 'Update' }}</PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
