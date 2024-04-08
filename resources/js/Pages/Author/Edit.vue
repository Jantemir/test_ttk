<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from '@inertiajs/vue3';
import AuthorForm from "@/Pages/Author/Partials/AuthorForm.vue";

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
</script>

<template>
    <Head :title="action === 'change' ? 'Edit Author' : 'New Author'" />


    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ action === 'change' ? 'Edit Author' : 'New Author' }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <AuthorForm
                        class="max-w-xl"
                        :item="item"
                        :action="action"
                        :can="can"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
