<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link } from '@inertiajs/vue3';
import Container from '@/Components/Container.vue';



const props = defineProps({
    jobs: Array
});

const archiveJob = (jobId) => {
    router.post(route('jobs.archive', jobId), {}, {
        onSuccess: () => console.log("Job archived!")
    });
};


</script>

<template>

    <div class="overflow-x-auto">
      <table class="min-w-full bg-white border border-gray-200">
        <thead>
          <tr class=" w-full bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
            <th class="py-2 px-4 text-left border">ID</th>
            <th class="py-2 px-4 text-left border">Job Title</th>
            <th class="py-2 px-4 text-left border">Location</th>
            <th class="py-2 px-4 text-left border">Employment Type</th>
            <th class="py-2 px-4 text-left border">Experience Level</th>
            <th class="py-2 px-4 text-left border">Status</th>
            <th class="py-2 px-4 text-left border">Action</th>
          </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
          <tr v-for="job in jobs" :key="job.id" class="border-b border-gray-200 hover:bg-gray-100">
            <td class="border border-gray-200 px-6 py-4">{{ job.id }}</td>
            <td class="border border-gray-200 px-6 py-4">{{ job.job_title }}</td>
            <td class="border border-gray-200 px-6 py-4">{{ job.location }}</td>
            <td class="border border-gray-200 px-6 py-4">{{ job.job_type }}</td>
            <td class="border border-gray-200 px-6 py-4">{{ job.experience_level }}</td>
            <td class="border border-gray-200 px-6 py-4">{{ job.status}}</td>
            <td class="border border-gray-200 px-6 py-4">
              <Link :href="route('jobs.view', { job: job.id })">
                <PrimaryButton class="mr-2">View</PrimaryButton>
              </Link>
              <Link :href="route('jobs.edit', { job: job.id })">
                <PrimaryButton class="mr-2">Edit</PrimaryButton>
              </Link>
              <button @click="archiveJob(job.id)">
                <PrimaryButton class="mr-2">Archive</PrimaryButton>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

 

</template>