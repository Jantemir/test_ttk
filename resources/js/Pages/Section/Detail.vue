<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from '@inertiajs/vue3';
import SimpleButton from "@/Components/SimpleButton.vue";
import DeleteResourceButton from "@/Components/DeleteResourceButton.vue";

defineProps({
    item: {
        type: Object
    },
    can: {
        type: Object
    }
})
</script>

<template>
    <Head :title="item.name" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ item.name }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                Description: <br>
                {{ item.description }}
            </div>

            <div class="py-2">
                <div class="flex flex-row justify-start mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div v-if="can?.update" class="mx-2">
                        <a :href="route('sections.edit', item.id)">
                            <SimpleButton>
                                Edit this section
                            </SimpleButton>
                        </a>
                    </div>
                    <div v-if="can?.delete" class="mx-2">
                        <DeleteResourceButton
                            :item="item"
                            :resourceName="{plural:'sections', singular:'section'}"
                            modalMessage="Sections cannot be restored."
                        >
                            Delete
                        </DeleteResourceButton>
                    </div>
                </div>
            </div>
        </div>




    </AuthenticatedLayout>
</template>

