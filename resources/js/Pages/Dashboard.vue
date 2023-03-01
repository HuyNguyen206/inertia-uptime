<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm, usePage} from '@inertiajs/vue3';
import SiteSelector from "@/Components/SiteSelector.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Endpoint from "@/Components/Endpoint.vue";

const page = usePage();
const props = defineProps({
    site: Object,
    endpoints: Object
})
const formEndpointCreate = useForm({
    location: null,
    frequency: page.props.frequencies.data[0].value,
    site_id: props.site.data.id
})

const formAddEmail = useForm({
    email: null
})

function addEmail(){
    formAddEmail.patch(route('sites.emails.store', props.site.data.id))
}

function storeEndpoint() {
    formEndpointCreate.post(route('endpoint.store'), {
        preserveScroll: true,
        onSuccess: function () {
            formEndpointCreate.reset()
        }
    })
}

</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ site.data.domain }}</h2>
                <site-selector :sites="$page.props.sites.data"/>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-2">
                    <h2 class="text-xl font-bold">New endpoint</h2>
                    <form @submit.prevent="storeEndpoint" class="mt-2 flex justify-start items-center space-x-2">
                        <div>
                            <TextInput v-model="formEndpointCreate.location" placeholder="/pricing"
                                       class="focus:border-none w-[600px] p-2 border "
                                       :class="{'border-red-500': formEndpointCreate.errors.frequency}"/>
                            <InputError class="mt-2" :message="formEndpointCreate.errors.location"/>
                            <input type="hidden" v-model="site_id">
                        </div>
                        <div>
                            <select v-model="formEndpointCreate.frequency" class="w-48 border-gray-300">
                                <option value="">Select frequency</option>
                                <option v-for="frequency in $page.props.frequencies.data" :key="frequency.value"
                                        :value="frequency.value">{{ frequency.label }}
                                </option>
                            </select>
                            <InputError class="mt-2" :message="formEndpointCreate.errors.frequency"/>
                        </div>

                        <PrimaryButton class="text-center justify-center">Create</PrimaryButton>
                    </form>

                    <div class="list-endpoint mt-4">
                        <h2 class="text-xl font-bold">Currently monitoring ({{ endpoints.data.length }})</h2>
                        <table class="w-full leading-9">
                            <thead class="bg-slate-200 py-4">
                            <th>Location</th>
                            <th>Frequency</th>
                            <th>Last check</th>
                            <th>Last status</th>
                            <th>Uptime</th>
                            <th>ACtion</th>
                            </thead>
                            <tbody>
                            <Endpoint v-for="endpoint in endpoints.data" :key="endpoint.id" :endpoint="endpoint"/>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <h2 class="text-xl font-semibold">Notification channels</h2>
                        <form @submit.prevent="addEmail" class="mt-2 flex justify-start items-center space-x-2">
                            <div>
                                <TextInput v-model="formAddEmail.email" placeholder="huy@gmail.com"
                                           class="focus:border-none w-[600px] p-2 border"/>
                                <InputError class="mt-2" :message="formAddEmail.errors.email"/>
                                <input type="hidden" v-model="site_id">
                            </div>
                            <PrimaryButton class="text-center justify-center">Add</PrimaryButton>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
