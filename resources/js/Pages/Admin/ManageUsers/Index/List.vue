<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Container.vue';
import { usePage } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {Link} from '@inertiajs/vue3';
import DangerButton from '@/Components/DangerButton.vue';
import { router } from '@inertiajs/vue3';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import { ref } from 'vue';


const page = usePage();



const props = defineProps ({
    all_users: Array,
    // user: Object

})


const showModal = ref(false);






</script>


<template>
    <AppLayout title="Manage Users">
        <template #header>
            List of Users
        
        </template>
        
        <Container class="py-16">
            <div class="overflow-x-auto">
    <table class="min-w-full bg-white border border-gray-200">
        <thead>
    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
        <th class="border border-gray-200 px-6 py-3 text-left">ID</th>
        <th class="border border-gray-200 px-6 py-3 text-left">Role</th>
        <th class="border border-gray-200 px-6 py-3 text-left">Name</th>
    </tr>
</thead>
        <tbody class="text-gray-600 text-sm font-light">
            <tr v-for="user in props.all_users" :key="user.id" class="border-b border-gray-200 hover:bg-gray-100">
                <td class="border border-gray-200 px-6 py-4">{{ user.id }}</td>
                <td class="border border-gray-200 px-6 py-4">{{ user.role }}</td>
                <td class="border border-gray-200 px-6 py-4">
    <!-- Conditionally display the name based on role -->
                <template v-if="user.role === 'graduate'">
                    {{ user.graduate_first_name }} {{ user.graduate_last_name }}
                </template>
                <template v-if="user.role === 'peso'">
                    {{ user.peso_first_name }} {{ user.peso_last_name }}
                </template>
                <template v-else-if="user.role === 'company'">
                    {{ user.company_name }}
                </template>
                <template v-else-if="user.role === 'institution'">
                    {{ user.institution_career_officer_first_name }} {{ user.institution_career_officer_last_name }}
                </template>
                <template v-else>
                    {{ user.name }}
                </template>
            </td>
              

             

               
            </tr>
        </tbody>
    </table>
</div>
<ConfirmationModal
            :show="showModal"
            @close="showModal = false"
            @confirm="handleDelete"
        >
            <template #title>
                Confirm Deletion
            </template>
            <template #content>
                Are you sure you want to delete this user?
            </template>
            <template #footer>
                <PrimaryButton @click="showModal = false">Cancel</PrimaryButton>
                <DangerButton @click="handleDelete">Delete</DangerButton>
            </template>
        </ConfirmationModal>
            

        </Container>
    </AppLayout>




</template>