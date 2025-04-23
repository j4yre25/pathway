<script setup>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import EmployersJobDetails from './EmployersJobDetails.vue'; // Adjust path if needed

defineProps({
  jobs: Array
});

const user = computed(() => usePage().props.auth.user);
const queryParams = new URLSearchParams(window.location.search);
const showDetails = queryParams.get('details'); // pass 'employer' to show

const isJobDetailsOpen = ref(false);
</script>


<template>
    <AppLayout title="Jobs">
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Job Listings</h2>
      </template>
  
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
  
        <!-- Accordion-like section to show EmployersJobDetails -->
        <div v-if="showDetails === 'employer'">
          <h2 id="job-details-heading">
            <button
              type="button"
              class="flex items-center justify-between w-full p-5 font-medium text-gray-500 border border-b-0 border-gray-200 rounded-t-xl hover:bg-white gap-3"
              @click="isJobDetailsOpen = !isJobDetailsOpen"
            >
              <h3 class="text-lg font-medium text-gray-900">View Job Details</h3>
              <svg data-accordion-icon class="w-3 h-3 shrink-0" :class="{ 'rotate-180': isJobDetailsOpen }" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </button>
          </h2>
          <div v-show="isJobDetailsOpen" id="job-details-body">
            <div class="p-5 border border-gray-200">
              <EmployersJobDetails :job="jobs[0]" :user="user" /> <!-- change index if needed -->
            </div>
          </div>
        </div>
  
      </div>
    </AppLayout>
  </template>
  