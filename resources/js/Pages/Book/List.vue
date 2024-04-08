<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from '@inertiajs/vue3';
import Pagination from "@/Components/Pagination.vue";
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
    items: {
        type: Array
    },
    pagination: {
        type: Array
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
    <Head title="Books" />


    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Books</h2>
        </template>

        <div class="py-12">
            <div class="flex justify-end max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="can?.viewHidden" class="mx-4">
                    <a :href="route('books.hidden')">
                        <SecondaryButton>
                            Show hidden
                        </SecondaryButton>
                    </a>
                </div>
                <div v-if="can?.create" class="mx-4">
                    <a :href="route('books.create')">
                        <PrimaryButton>
                            Add new book
                        </PrimaryButton>
                    </a>
                </div>
            </div>

            <div v-if="items?.length > 0" class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <ul>
                    <li v-for="item in items">
                        <a :href="route('books.show', item.id)" class="inline-flex items-center px-4 py-2 text">
                            {{ item.name }}
                        </a>
                    </li>
                </ul>
            </div>
            <div v-else-if="items.length === 0" class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                Nothing's here... <br>
                Try something else
            </div>
        </div>

        <div v-if="pagination.last_page > 1  && pagination.last_page >= pagination.current_page" class="py-12 flex justify-center">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <Pagination
                    :pagination = "pagination"
                />
            </div>
        </div>


    </AuthenticatedLayout>
</template>

