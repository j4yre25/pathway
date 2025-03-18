<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Welcome from '@/Components/Welcome.vue';

const { props } = usePage();
const userNotApproved = computed(() => props.userNotApproved ?? false);
console.log(props.userNotApproved)

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    
                    <div v-if="userNotApproved" class="alert alert-warning p-4 mb-4 border border-yellow-300 bg-yellow-100 text-yellow-800 rounded">
                        Your account is not approved yet. Some features may be disabled.
                    </div>

                    <!-- Example of conditionally rendering features -->
                    <div v-if="!userNotApproved">
                        <Welcome />
                        <p>Welcome to your dashboard! You can access all features.</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>