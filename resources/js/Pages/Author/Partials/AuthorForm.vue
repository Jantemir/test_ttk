<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm, usePage } from '@inertiajs/vue3';

defineProps({
    action: {
        type: String,
        default: 'create'
    },
    item: {
        type: Object,
    },
    can: {
        type: Object
    }
});

const item = usePage().props.item;
const isChange = usePage().props.action === 'change';

const form = useForm({
    name: item?.name,
    mother_country: item?.mother_country,
    comment: item?.comment,
    active: item?.active == null ? true : Boolean(item?.active),
    _method: isChange ? 'patch' : 'post',
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Author information</h2>

            <p class="mt-1 text-sm text-gray-600">
                Fill all required fields to {{ isChange ? 'change the author\'s info' : 'create a new author' }}
            </p>
        </header>

        <form @submit.prevent="isChange ? form.post(route('authors.update', item.id), [{'id': item.id}]) : form.post(route('authors.store'))" class="mt-6 space-y-6">
            <input v-if="isChange" type="hidden" id="id" :value="item.id">
            <div>
                <InputLabel for="name" value="Full name" />

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
                <InputLabel for="mother_country" value="Mother Country" />

                <TextInput
                    id="mother_country"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.mother_country"
                    required
                    autocomplete="mother_country"
                />

                <InputError class="mt-2" :message="form.errors.mother_country" />
            </div>

            <div>
                <InputLabel for="comment" value="Comments" />

                <TextInput
                    id="comment"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.comment"
                    required
                    autocomplete="comment"
                />

                <InputError class="mt-2" :message="form.errors.comment" />
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
