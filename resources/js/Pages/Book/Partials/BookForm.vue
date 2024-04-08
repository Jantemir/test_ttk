<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SimpleButton from '@/Components/SimpleButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import {FwbFileInput} from "flowbite-vue";
import MultiSelect from 'primevue/multiselect';
import {ref} from "vue";

defineProps({
    action: {
        type: String,
    },
    item: {
        type: Object,
    },
    sections: {
        type: Array,
    },
    authors: {
        type: Array,
    },
    can: {
        type: Object
    }
});

const item = usePage().props.item;
const isChange = usePage().props.action === 'change';
const file = ref(null);

const form = useForm({
    name: item?.name,
    year: item?.year,
    cover: ref(null),
    description: item?.description,
    authors: item?.authors ?? ref(null),
    sections: item?.sections ?? ref(null),
    active: item?.active == null ? true : Boolean(item?.active),
    _method: isChange ? 'patch' : 'post'
});

const submitForm = () => {
    if (form.cover === null) {
        delete form.cover;
    }
    isChange ? form.post(route('books.update', item.id), [{'id': item.id}]) : form.post(route('books.store'))
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Book information</h2>

            <p class="mt-1 text-sm text-gray-600">
                Fill all required fields to {{ isChange ? 'change the book\'s info' : 'create a new book' }}
            </p>
        </header>

        <form @submit.prevent="submitForm" class="mt-6 space-y-6">
            <input v-if="isChange" type="hidden" id="id" :value="item.id">
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="year" value="Year" />

                <TextInput
                    id="year"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.year"
                    required
                    autocomplete="year"
                />

                <InputError class="mt-2" :message="form.errors.year" />
            </div>

            <div>
                <InputLabel for="description" value="Description" />

                <TextInput
                    id="description"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.description"
                    required
                    autocomplete="description"
                />

                <InputError class="mt-2" :message="form.errors.description" />
            </div>

            <div>
                <InputLabel for="sections" value="Sections" />

                <div class="card flex justify-between">
                    <MultiSelect v-model="form.sections" :options="sections" filter optionLabel="name" placeholder="Select Sections"
                                 :maxSelectedLabels="2" class="w-full md:w-[20rem]" />

                    <a v-if="can?.create?.section" :href="route('sections.create')" target="_blank" class="flex flex-col justify-center">
                        <SimpleButton>
                            Add new section
                        </SimpleButton>
                    </a>
                </div>

                <InputError class="mt-2" :message="form.errors.sections" />
            </div>

            <div>
                <InputLabel for="authors" value="Authors" />

                <div class="card flex justify-between">
                    <MultiSelect v-model="form.authors" :options="authors" filter optionLabel="name" placeholder="Select Authors"
                                 :maxSelectedLabels="2" class="w-full md:w-[20rem]" />

                    <a v-if="can?.create?.author" :href="route('authors.create')" target="_blank" class="flex flex-col justify-center">
                        <SimpleButton>
                            Add new author
                        </SimpleButton>
                    </a>
                </div>

                <InputError class="mt-2" :message="form.errors.authors" />
            </div>

            <div>
                <InputLabel for="cover" value="Cover" />

                <fwb-file-input
                    v-model="form.cover"
                    dropzone
                />

                <InputError class="mt-2" :message="form.errors.cover" />
            </div>

            <div v-if="can?.hide" class="block mt-4">
                <label class="flex items-center">
                    <Checkbox name="active" v-model:checked="form.active" />
                    <span class="ms-2 text-sm text-gray-600">Active</span>
                </label>
                <InputError class="mt-2" :message="form.errors.active" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">{{ isChange ? 'Submit' : 'Create' }}</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">{{ isChange ? 'Changed' : 'Created' }}</p>
                </Transition>
            </div>

        </form>
    </section>
</template>
