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

const approveJob = (job) => {
    router.post(route('jobs.approve', { job: job.id }), {});
};
</script>

<template>

    <div class="overflow-x-auto">
      <table class="min-w-full bg-white border border-gray-200">
        <thead>
          <tr class=" w-full bg-gray-100 text-gray-600 uppercase text-sm leading-normal">

            <th class="py-2 px-4 text-left border">Job Title</th>
            <th class="py-2 px-4 text-left border">Location</th>
            <th class="py-2 px-4 text-left border">Employment Type</th>
            <th class="py-2 px-4 text-left border">Experience Level</th>
            <th class="py-2 px-4 text-left border">Status</th>
   
          </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
          <tr v-for="job in jobs" :key="job.id" class="border-b border-gray-200 hover:bg-gray-100">
            <td class="border border-gray-200 px-6 py-4">{{ job.job_title }}</td>
            <td class="border border-gray-200 px-6 py-4">{{ job.location }}</td>
            <td class="border border-gray-200 px-6 py-4">{{ job.job_type }}</td>
            <td class="border border-gray-200 px-6 py-4">{{ job.experience_level }}</td>
            <td class="border border-gray-200 px-6 py-4">
                <span v-if="job.is_approved === 1" class="text-green-600 font-semibold">Approved</span>
                <span v-else-if="job.is_approved === 0" class="text-red-600 font-semibold">Disapproved</span>
                <span v-else class="text-yellow-600 font-semibold">Pending</span>
              </td>
            
          </tr>
        </tbody>
      </table>
    </div>

 

</template>