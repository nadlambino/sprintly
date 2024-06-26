<script setup>
import { computed, ref, onMounted } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useTaskApi } from '@/Utils/task';
import { useStatusStore } from '@/Utils/status';
import { usePriorityLevelApi } from '@/Utils/priority-level';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/Shared/InputLabel.vue';
import TextInput from '@/Components/Shared/TextInput.vue';
import RadioInput from '@/Components/Shared/RadioInput.vue';
import PrimaryButton from '@/Components/Shared/PrimaryButton.vue';
import Alert from '@/Components/Shared/Alert.vue';
import InputError from '@/Components/Shared/InputError.vue';
import Toggle from '@/Components/Shared/Toggle.vue';
import SecondaryButton from '@/Components/Shared/SecondaryButton.vue';
import SubMenu from '@/Components/Tasks/SubMenu.vue';
import Combobox from '@/Components/Shared/Combobox.vue';
import SelectInput from '@/Components/Shared/SelectInput.vue';
import ConfirmButton from '@/Components/Shared/ConfirmButton.vue';
import DateTimePicker from '@/Components/Shared/DateTimePicker.vue';
import { watch } from 'vue';

const props = defineProps({
    task: {
        type: Object,
        default: () => ({})
    }
});

const { update, create, getParents } = useTaskApi();

const isNew = computed(() => props.task?.id === undefined);

const statusStore = useStatusStore();
const form = useForm({
    id: props.task?.id || null,
    parent_id: props.task?.parent_id || null,
    title: props.task?.title || '',
    content: props.task?.content || '',
    images: [],
    status_id: props.task?.status_id || collect(statusStore.statuses).first()?.id,
    start_at: props.task?.start_at || null,
    due_at: props.task?.due_at || null,
    priority_level_id: props.task?.priority_level_id,
    publish: props.task?.published_at ? true : false,
    replace_images: false
});

const parents = ref([]);

const getParentsList = async (value) => {
    await getParents({ except: form.id, title: value })
        .then(({ data }) => {
            parents.value = data?.data?.map((parent) => ({ value: parent.id, label: parent.title }));
        });
}

const setDefaultOptionsForParent = () => {
    if (! isNew.value && props.task?.parent) {
        parents.value = [{ value: form.parent_id, label: props.task?.parent?.title }];
    } else {
        getParentsList();
    }
}

onMounted(setDefaultOptionsForParent);

const { data: priorityLevels } = usePriorityLevelApi();
const priorityOptions = computed(() => priorityLevels.value?.map((priority) => ({ value: priority.id, label: priority.name })) || []);
watch(priorityOptions, () => form.priority_level_id = priorityOptions.value[0].value);

const success = ref(false);
const message = ref(null);
const errors = ref({});

const images = computed(() => Array.from(form.images).map((image) => URL.createObjectURL(image)));
const savedImages = computed(() => props.task?.images?.map((image) => window.location.origin + '/' + image.path));
const hasServerError = computed(() => success.value === false && errors?.value?.length === 0 && message?.value !== null);

const errorHasImageServerError = computed(() => {
    return Object.keys(errors.value).some(key => key.toLowerCase().includes('images.'));
});

const submit = async () => {
    success.value = false;
    errors.value = {};
    const data = form.data();

    if (isNew.value) {
        delete data.id;
        delete data.replace_images;

        return await create(data, { 'Content-Type': 'multipart/form-data' })
            .then(then)
            .catch(error);
    }

    return await update(data.id, data, { 'Content-Type': 'multipart/form-data' })
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

        <div class="p-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <form class="flex flex-col gap-5 p-5 max-w-2xl m-auto" @submit.prevent enctype="multipart/form-data">
                        <div class="flex flex-col gap-2">
                            <InputLabel for="parent" value="Parent" />
                            <Combobox id="parent" v-model="form.parent_id" :options="parents" @change="getParentsList" placeholder="Search parent by title..." />
                        </div>

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
                            />
                            <InputError v-for="error in errors?.title || []" :message="error" />
                        </div>

                        <div class="flex flex-col gap-2">
                            <InputLabel for="content" value="Content" />
                            <TextInput
                                id="content"
                                type="textarea"
                                class="mt-1 block w-full"
                                v-model="form.content"
                                maxlength="10000"
                            />
                            <InputError v-for="error in errors?.content || []" :message="error" />
                        </div>

                        <div class="flex flex-col gap-2">
                            <InputLabel for="status" value="Status" />
                            <div class="flex gap-5 flex-wrap">
                                <div v-for="status in statusStore.statuses" class="flex gap-2 items-center">
                                    <RadioInput :key="status.id" :value="status.id" v-model="form.status_id" name="status" />
                                    <InputLabel :for="status.id" :value="status.name" class="capitalize cursor-pointer"/>
                                </div>
                            </div>
                            <InputError v-for="error in errors?.status_id || []" :message="error" />
                        </div>

                        <div class="flex flex-col gap-2">
                            <InputLabel for="priority-level" value="Priority" />
                            <SelectInput
                                id="priority-level"
                                v-model="form.priority_level_id"
                                :options="priorityOptions"
                            />
                        </div>

                        <div class="flex flex-col md:flex-row gap-5">
                            <div class="flex flex-col gap-2 w-full">
                                <InputLabel for="start_at" value="Start At" />
                                <DateTimePicker v-model="form.start_at" id="start_at" />
                                <InputError v-for="error in errors?.start_at || []" :message="error" />
                            </div>
                            <div class="flex flex-col gap-2 w-full">
                                <InputLabel for="due_at" value="Due At" />
                                <DateTimePicker v-model="form.due_at" id="due_at" />
                                <InputError v-for="error in errors?.due_at || []" :message="error" />
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <div class="flex justify-between items-center">
                                <InputLabel for="publish" value="Publish" />
                                <small class="text-muted text-xs text-right">Only published tasks will be visible in board page.</small>
                            </div>
                            <Toggle id="publish" v-model="form.publish"/>
                        </div>

                        <div class="flex flex-col gap-3">
                            <InputLabel for="images" value="Image(s)" />
                            <input type="file" class="focus:ring-0 focus:outline-none"ame="images" accept="image/*" multiple @input="form.images = $event.target.files" />
                            <InputError v-for="error in errors?.images || []" :message="error" />

                            <template v-if="!isNew">
                                <div class="flex justify-between items-center">
                                    <InputLabel for="replaced-images" value="Replace Image(s)" :class="{ 'text-muted cursor-not-allowed': images.length === 0 }" />
                                    <small class="text-muted text-xs text-right">When checked, all images will be replaced with new ones.</small>
                                </div>
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
                            <ConfirmButton
                                class="w-24 flex justify-center inline-flex items-center px-3 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase
                                    tracking-widest hover:bg-primary/80 focus:bg-primary/80 active:bg-primary focus:outline-none focus:ring-2 focus:ring-primary
                                    focus:ring-offset-2 transition ease-in-out duration-150"
                                :text="isNew ? 'Create' : 'Update'"
                                :message="isNew ? 'Are you sure you want to create this task?' : 'Are you sure you want to update this task?'"
                                @confirm="submit"
                            />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
