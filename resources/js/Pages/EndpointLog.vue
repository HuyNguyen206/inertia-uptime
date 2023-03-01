<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, router} from '@inertiajs/vue3';
import SiteSelector from "@/Components/SiteSelector.vue";
import {ref} from "vue";

const props = defineProps({
    logs: Object,
    endpoint: Object
})
const currentPage = ref(1);
const showModalResponse = ref(false)
const response_body = ref('')

function showModal(response) {
    response_body.value = response
    showModalResponse.value = true
}

function changePage(){
    router.get(route('endpoints.logs.index', {endpoint : props.endpoint.data.id, page: currentPage.value}), [], {
        preserveScroll: true,
        preserveState: true
    })
}
</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <Link :href="route('dashboard')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/>
                    </svg>
                </Link>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ endpoint.data.fullurl }}</h2>
                <site-selector :sites="$page.props.sites.data"/>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-2">
                    <h2 class="text-xl font-bold">Logs</h2>
                    <div class="list-endpoint mt-4">
                        <table class="w-full leading-9">
                            <thead class="bg-slate-200 py-4">
                                <th style="width: 30%">Checked at</th>
                                <th style="width: 20%">Response code</th>
                                <th>Response body</th>
                            </thead>
                            <tbody>
                                <tr v-for="log in logs.data" :key="log.id">
                                    <td class="text-center mt-2">
                                        <time :datetime="log.last_checked?.datetime ?? ''"
                                              :title="log.last_checked?.datetime ?? ''">
                                            {{ log.last_checked.human ?? '' }} ({{log.last_checked.datetime}})
                                        </time>
                                    </td>
                                    <td class="text-center mt-2">
                                        <span :class="[log.is_success  ? 'bg-green-200' : 'bg-red-200']"
                                            class="inline-block px-2 py-1">
                                        {{ log.last_status }}
                                        </span>
                                    </td>
                                    <td class="text-center mt-2">
                                        <button type="button" class="px-2 bg-green-600 text-white rounded hover:bg-green-500 transition"
                                                v-if="!log.is_success" href="" @click.prevent="showModal(log.response_body)">View response</button>
                                        <span v-else>-</span>
                                    </td>
                                </tr>
                            <tr v-if="!logs.data.length">
                                <td colspan="3" class="text-center">No logs yet</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="logs.meta.total" class="mt-4">
                        <vue-awesome-paginate
                            :total-items="logs.meta.total"
                            :items-per-page="logs.meta.per_page"
                            v-model="currentPage"
                            :on-click="changePage"
                        />
                    </div>
                    <vue-final-modal name="endpointResponseBody" v-model="showModalResponse" classes="modal-container" content-class="modal-content w-96" :esc-to-close="true">
                        <code v-text="response_body" class="overflow-y-visible">

                        </code>
                    </vue-final-modal>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
.pagination-container {
    display: flex;
    width: 100%;
    column-gap: 10px;
    justify-content: center;
}
.paginate-buttons {
    height: 40px;
    width: 40px;
    border-radius: 20px;
    cursor: pointer;
    background-color: rgb(242, 242, 242);
    border: 1px solid rgb(217, 217, 217);
    color: black;
}
.paginate-buttons:hover {
    background-color: #d8d8d8;
}
.active-page {
    background-color: #3498db;
    border: 1px solid #3498db;
    color: white;
}
.active-page:hover {
    background-color: #2988c8;
}
</style>
