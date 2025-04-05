<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Container.vue';
import { usePage } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link } from '@inertiajs/vue3';
import DangerButton from '@/Components/DangerButton.vue';
import { router } from '@inertiajs/vue3';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import { ref, computed } from 'vue';


const page = usePage();



const props = defineProps({
    all_users: Array,
    // user: Object

})



const filters = ref({
    role: 'all',

});


const applyFilters = () => {
    router.get(route('admin.manage_users.list'), filters.value, { preserveState: true });
};


const filteredUsers = computed(() => {
    if (filters.value.role === 'all') {
        return props.all_users;
    }
    return props.all_users.filter(user => user.role === filters.value.role);
});

const showModal = ref(false);






</script>


<template>
    <AppLayout title="Manage Users">
        <template #header>
            List of Users

        </template>

        <Container class="py-16">
            <div class="mb-6">
                <form @submit.prevent="applyFilters" class="flex flex-wrap gap-4">
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">User Level</label>
                        <select v-model="filters.role" id="role"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="all">All</option>
                            <option value="company">Company</option>
                            <option value="institution">Institution</option>
                        </select>
                    </div>
             
                    <div class="flex items-end">
                        <PrimaryButton type="submit">Apply Filters</PrimaryButton>
                    </div>
                </form>

            </div>

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
                        <tr v-for="user in filteredUsers" :key="user.id" class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="border border-gray-200 px-6 py-4">{{ user.id }}</td>
                            <td class="border border-gray-200 px-6 py-4">{{ user.role }}</td>
                            <td class="border border-gray-200 px-6 py-4">
                                <!-- Conditionally display the name based on role -->

                                <template v-if="user.role === 'company'">
                                    {{ user.company_name }}
                                </template>
                                <template v-else-if="user.role === 'institution'">
                                    {{ user.institution_career_officer_first_name }} {{
                                        user.institution_career_officer_last_name }}
                                </template>
                                <template v-else>
                                    {{ user.name }}
                                </template>
                            </td>





                        </tr>
                    </tbody>
                </table>
            </div>
            <ConfirmationModal :show="showModal" @close="showModal = false" @confirm="handleDelete">
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