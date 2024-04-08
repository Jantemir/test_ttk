<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import InputError from "@/Components/InputError.vue";

const submitSearch = () => {
    form.get(route('books.search'));
}

const form = useForm({
    q: ref(null)
});
</script>

<template>
    <form @submit.prevent="submitSearch" class="flex items-center max-w-sm mx-auto">
        <label for="simple-search" class="sr-only">Search</label>
        <div class="relative w-full">
            <input v-model="form.q" type="text" id="simple-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search books by name or authors" required />
        </div>
        <button type="submit" class="focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900 focus:bg-gray-700 hover:bg-gray-700 bg-gray-800 p-2.5 ms-2 text-sm font-medium text-white bg-gray-700 rounded-lg border">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
            </svg>
            <span class="sr-only">Search</span>
        </button>
        <InputError class="mt-2" :message="form.errors.q" />
    </form>
</template>

<style scoped>

</style>
