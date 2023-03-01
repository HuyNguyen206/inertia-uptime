<template>
    <tr>
        <td class="text-center">
            <input class="rounded border-gray-300 mt-2" type="text" v-model="formEditEndpoint.location" v-if="isEdit">
            <span v-else>
                <Link :href="route('endpoints.logs.index', endpoint.id)">{{ endpoint.location }}</Link>
            </span>
            <InputError class="mt-2" :message="formEditEndpoint.errors.location"/>
        </td>
        <td class="text-center">
            <select v-model="formEditEndpoint.frequency" v-if="isEdit" class="w-48 border-gray-300">
                <option value="">Select frequency</option>
                <option v-for="frequency in $page.props.frequencies.data" :key="frequency.value"
                        :value="frequency.value">{{ frequency.label }}
                </option>
            </select>
            <span v-else>
             {{ endpoint.frequency }}
            </span>
            <InputError class="mt-2" :message="formEditEndpoint.errors.frequency"/>

        </td>
        <td class="text-center mt-2">
            <time :datetime="endpoint.lastest_log.last_checked?.datetime ?? ''"
                  :title="endpoint.lastest_log.last_checked?.datetime ?? ''">
                {{ endpoint.lastest_log.last_checked?.human ?? '' }}
            </time>
        </td>
        <td class="text-center mt-2">
            <span :class="[endpoint.lastest_log?.is_success  ? 'bg-green-200' : endpoint.lastest_log.last_checked ? 'bg-red-200' : '' ]"
                  class="inline-block px-2 py-1">
            {{ endpoint.lastest_log.last_status }}
        </span>
        </td>
        <td class="text-center mt-2">{{ endpoint.up_time }}</td>
        <td class="flex space-x-2 justify-center mt-2">
            <a class="cursor-pointer" @click.prevent="toggleEditView">
                <span class="text-purple-700 " v-if="!isEdit">Edit</span>
                <span class="text-gray-400" v-else>
                    Done
                </span>
            </a>
            <a v-if="isEdit" @click.prevent="isEdit = false" class="text-gray-400 cursor-pointer">Cancel</a>
            <a v-else @click.prevent="deleteEndpoint" class="text-red-700 cursor-pointer">Delete</a>
        </td>
    </tr>
</template>

<script setup>
import {Link, router, useForm} from "@inertiajs/vue3";
import {ref} from "vue";
import InputError from "@/Components/InputError.vue";

const isEdit = ref(false);
const props = defineProps({
    endpoint: Object
})
const formEditEndpoint = useForm({
    location: props.endpoint.location,
    frequency: props.endpoint.frequency_value
})

function deleteEndpoint() {
    if (confirm('Are you sure to delete this endpoint?')) {
        router.delete(route('endpoints.delete', props.endpoint.id))
    }
}

function toggleEditView() {
    if (!isEdit.value) {
        isEdit.value = !isEdit.value
    } else {
        formEditEndpoint.put(route('endpoints.update', props.endpoint.id), {
            preserveScroll: true
        })
        if (page.props.errors.length === 0) {
            isEdit.value = !isEdit.value
        }
    }
}
</script>

<style scoped>

</style>
