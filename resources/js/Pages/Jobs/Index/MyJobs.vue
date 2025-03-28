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
            <th class="py-2 px-4 text-left border">ID</th>
            <th class="py-2 px-4 text-left border">Job Title</th>
            <th class="py-2 px-4 text-left border">Location</th>
            <th class="py-2 px-4 text-left border">Employment Type</th>
            <th class="py-2 px-4 text-left border">Experience Level</th>
            <th class="py-2 px-4 text-left border">Status</th>
   
          </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
          <tr v-for="job in jobs" :key="job.id" class="border-b border-gray-200 hover:bg-gray-100">
            <td class="border border-gray-200 px-6 py-4">{{ job.id }}</td>
            <td class="border border-gray-200 px-6 py-4">{{ job.job_title }}</td>
            <td class="border border-gray-200 px-6 py-4">{{ job.location }}</td>
            <td class="border border-gray-200 px-6 py-4">{{ job.job_type }}</td>
            <td class="border border-gray-200 px-6 py-4">{{ job.experience_level }}</td>
            <td class="border border-gray-200 px-6 py-4">
                <!-- Status Badge -->
                <span :class="{
                    'bg-green-100 text-green-800': job.status === 'active',
                    'bg-red-100 text-red-800': job.status === 'inactive',
                    'bg-yellow-100 text-yellow-800': job.status === 'pending',
                    'bg-blue-100 text-blue-800': job.status === 'archived'
                }" class="px-3 py-1 text-sm font-semibold rounded-full">
                    {{ job.status }}
                </span>
            </td>
            
          </tr>
        </tbody>
      </table>
    </div>

 

</template>