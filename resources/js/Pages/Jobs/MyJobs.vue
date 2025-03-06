<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    jobs: Array

})
</script>

<template>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div v-for="job in jobs" :key="job.id" class="col-span-1 border bg-white rounded-lg shadow p-4">
        <h3 class="font-bold text-gray-900">{{job.name }}</h3>
        <p class="text-gray-600">{{job.description }}</p>

        <div class="mt-4">
            <Link :href="route('jobs.view', {job: job.id})" class="mr-2">View</Link>
            <Link :href="route('jobs.edit', {job: job.id})">Edit</Link>
        </div>

    </div>
    </div>
</template>