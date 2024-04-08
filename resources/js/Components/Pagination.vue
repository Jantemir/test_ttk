<script setup>

const props = defineProps({
    pagination: {
        type: Array,
        required: true,
    },
});

const getUriParams = (link) => {
    let qd = {};
    if (link.indexOf('?') > -1) {
        const search = link.slice(link.indexOf('?'));
        if (search) search.substring(1).split`&`.forEach(item => {let [k,v] = item.split`=`; v = v && decodeURIComponent(v); (qd[k] = qd[k] || []).push(v)});
        return qd;
    }
    return {};
}
const addCurrentUriParams = (link) => {
    const params = {...getUriParams(window.location.href), ...getUriParams(link)};
    let search = '?';
    Object.keys(params).forEach(key => {
        search += key + '=' + params[key] + '&';
    })
    return link.slice(0, link.indexOf('?')) + search.slice(0, search.length - 1);
}
</script>

<template>
    <nav aria-label="Page navigation">
        <ul class="inline-flex -space-x-px text-base h-10">
            <li v-if="pagination.prev_page_url">
                <a :href="addCurrentUriParams(pagination.prev_page_url)" class="flex items-center justify-center px-4 h-10 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
            </li>
            <li  v-if="pagination.current_page > 1">
                <a :href="addCurrentUriParams(pagination.first_page_url)" class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">1</a>
            </li>
            <li v-if="pagination.current_page > 2">
                <a :href="addCurrentUriParams(pagination.path + '/?page=' + Math.ceil(pagination.current_page/2))" class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">...</a>
            </li>
            <li>
                <a :href="addCurrentUriParams(pagination.path + '/?page=' + pagination.current_page)" aria-current="page" :class=" (pagination.current_page === 1 ? 'rounded-s-lg ' : '') + (pagination.current_page === pagination.last_page ? 'rounded-e-lg ' : '') + 'flex items-center justify-center px-4 h-10 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white'">{{ pagination.current_page }}</a>
            </li>
            <li v-if="pagination.current_page < pagination.last_page - 1">
                <a :href="addCurrentUriParams(pagination.path + '/?page=' + ((+pagination.current_page) + (+Math.ceil((pagination.last_page - pagination.current_page)/2))))" class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">...</a>
            </li>
            <li v-if="pagination.current_page < pagination.last_page">
                <a :href="addCurrentUriParams(pagination.last_page_url)" class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{ pagination.last_page }}</a>
            </li>
            <li v-if="pagination.next_page_url">
                <a :href="addCurrentUriParams(pagination.next_page_url)" class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
            </li>
        </ul>
    </nav>
</template>
