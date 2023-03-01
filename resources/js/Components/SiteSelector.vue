<template>
    <VDropdown>
        <!-- This will be the popover target (for the events and position) -->
        <button class="flex justify-center items-center">
            Click me
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-2 w-3 h-3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
        </button>
        <!-- This will be the content of the popover -->
        <template #popper>
            <ul class="space-y-1">
                <li  v-for="site in sites" :key="site.id">
                    <Link :href="route('dashboard', site.id)" class="w-full block p-2 hover:bg-gray-300 cursor-pointer">{{site.domain}}</Link>
                </li>
                <li>
                    <button v-close-popper @click="showCreateSiteModal = true" class="w-full p-2 text-left hover:bg-gray-300 text-purple-700 cursor-pointer">Add a site</button>
                </li>
            </ul>
        </template>
    </VDropdown>
    <vue-final-modal name="siteCreateModal" v-model="showCreateSiteModal" classes="modal-container" content-class="modal-content w-96" :esc-to-close="true">
        <h2 class="font-bold text-xl">New site</h2>

        <form @submit.prevent="storeSite" class="flex flex-col">
            <TextInput v-model="siteForm.domain" placeholder="https://gamek.vn/" class="mt-2 p-2 border " :class="{'border-red-500': siteForm.errors.domain}"/>
            <InputError class="mt-2" :message="siteForm.errors.domain" />
            <PrimaryButton class="text-center mt-2 justify-center">Create</PrimaryButton>
        </form>
    </vue-final-modal>
</template>

<script setup>
import {Link, useForm} from "@inertiajs/vue3";
import {ref} from "vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {$vfm} from "vue-final-modal";
import InputError from "@/Components/InputError.vue";

defineProps({
    sites: Array,
    errors: Object
})

const siteForm = useForm({
    domain: null
})

function storeSite(){
    siteForm.post(route('sites.store'), {
        preserveScroll: true,
        onSuccess: function () {
            siteForm.reset()
            $vfm.hide('siteCreateModal')
        }
    })
}

let showCreateSiteModal = ref(false)
</script>

<style scoped>

</style>

<style scoped>
 .dark-mode div::v-deep .modal-content {
     border-color: #2d3748;
     background-color: #1a202c;
 }
</style>
