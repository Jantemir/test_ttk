<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { usePage, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const confirmingResourceDeletion = ref(false);
const passwordInput = ref(null);

defineProps({
    item: {
        type: Object
    },
    resourceName: {
        type: Object
    },
    modalMessage: {
        type: String,
    }
})

const props = usePage().props;

const form = useForm({
    _method: 'delete',
});

const confirmResourceDeletion = () => {
    confirmingResourceDeletion.value = true;
};

const deleteResource = (name, id) => {
    console.log(props)
    form.delete(route(name + '.destroy', id), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingResourceDeletion.value = false;

    form.reset();
};
</script>

<template>
        <DangerButton @click="confirmResourceDeletion">Delete</DangerButton>

        <Modal :show="confirmingResourceDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete this {{ resourceName.singular }}?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ modalMessage }}
                </p>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteResource(resourceName.plural, item.id)"
                    >
                        Delete
                    </DangerButton>
                </div>
            </div>
        </Modal>
</template>
