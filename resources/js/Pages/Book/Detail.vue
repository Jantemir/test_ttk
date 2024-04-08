<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from '@inertiajs/vue3';
import SimpleButton from "@/Components/SimpleButton.vue";
import DeleteResourceButton from "@/Components/DeleteResourceButton.vue";

defineProps({
    item: {
        type: Object
    },
    auth: {
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
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <img :src="item.cover" :alt="item.name" class="w-auto h-48">
                Name: {{ item.name }}<br>
                Year: {{ item.year }}<br>
                Description: {{ item.description }}<br>
                <div v-if="item.authors?.length > 0">
                    Authors:
                    <a v-for="(author, index) in item.authors" :href="route('authors.show', author.id)" class="text-blue-600">
                        {{ author.name }}
                        <template v-if="index<item.authors.length - 1">,</template>
                    </a><br>
                </div>
                <div v-if="item.sections?.length > 0">
                    Sections:
                    <a v-for="(section, index) in item.sections" :href="route('sections.show', section.id)" class="text-blue-600">
                        {{ section.name }}
                        <template v-if="index<item.sections.length - 1">,</template>
                    </a><br>
                </div>
            </div>

            <div class="py-2">
                <div class="flex flex-row justify-start mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div v-if="can?.update" class="mx-2">
                        <a :href="route('books.edit', item.id)">
                            <SimpleButton>
                                Edit this book
                            </SimpleButton>
                        </a>
                    </div>
                    <div v-if="can?.delete" class="mx-2">
                        <DeleteResourceButton
                            :item="item"
                            :resourceName="{plural:'books', singular:'book'}"
                            modalMessage="Books cannot be restored."
                        >
                            Delete
                        </DeleteResourceButton>
                    </div>
                </div>
            </div>
        </div>


    </AuthenticatedLayout>
</template>

