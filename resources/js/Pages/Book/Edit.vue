<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from '@inertiajs/vue3';
import BookForm from "@/Pages/Book/Partials/BookForm.vue";

defineProps({
    action: {
        type: String,
        default: 'create'
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
</script>

<template>
    <Head :title="action === 'change' ? 'Edit Book' : 'New Book'" />


    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ action === 'change' ? 'Changing the book' : 'New book' }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <BookForm
                        class="max-w-xl"
                        :authors="authors"
                        :sections="sections"
                        :item="item"
                        :action="action"
                        :can="can"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

