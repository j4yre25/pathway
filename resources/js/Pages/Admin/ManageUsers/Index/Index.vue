<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Container.vue';
import { usePage } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link } from '@inertiajs/vue3';
import DangerButton from '@/Components/DangerButton.vue';
import { router } from '@inertiajs/vue3';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import { ref } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';



const page = usePage();



const props = defineProps({
    all_users: Array,
    // user: Object

})


const showModal = ref(false);
const userToDelete = ref(null);

const archiveUser = (user) => {
    router.delete(route('admin.manage_users.delete', { user: user.id }));
};

const confirmDelete = (user) => {
    userToDelete.value = user;
    showModal.value = true;
};

const handleDelete = () => {
    if (userToDelete.value) {
        deleteUser(userToDelete.value);
        showModal.value = false;
        userToDelete.value = null;
    }
};
const approveUser = (user) => {

    router.post(route('admin.manage_users.approve', { user: user.id }), {

    });
};
const open = ref(false)

const disapproveUser = (user) => {

    console.log('Disapproving user:', user);

    router.post(route('admin.manage_users.disapprove', { user: user.id }), {
        is_approved: false
    });
};




</script>


<template>
    <AppLayout title="Manage Users">
        <template #header>
            Manage Users

        </template>

        <Container class="py-4">

            <div class="mt-8 mb-8">
                <Link v-if="page.props.roles.isPeso" :href="route('admin.manage_users.list')"
                    :active="route().current('admin.manage_users.list')">
                <PrimaryButton>List Of Users</PrimaryButton>
                </Link>
            </div>
            <div class="overflow-x-auto">


                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                            <th class="border border-gray-200 px-6 py-3 text-left">ID</th>
                            <th class="border border-gray-200 px-6 py-3 text-left">Role</th>
                            <th class="border border-gray-200 px-6 py-3 text-left">Name</th>
                            <th class="border border-gray-200 px-6 py-3 text-left">Email</th>
                            <th class="border border-gray-200 px-6 py-3 text-left">Status</th>
                            <th class="border border-gray-200 px-6 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        <tr v-for="user in all_users.filter(u => u.role === 'company' || u.role === 'institution')"
                            :key="user.id" class="border-b border-gray-200 hover:bg-gray-100">
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
                            <td class="border border-gray-200 px-6 py-4">{{ user.email }}</td>


                            <td class="border border-gray-200 px-6 py-4">
                                <span v-if="user.is_approved" class="text-green-600 font-semibold">Approved</span>
                                <span v-else class="text-red-600 font-semibold">Pending</span>
                            </td>
                            <td class="border border-gray-200 px-6 py-4">
                                <PrimaryButton class= "mr-2" @click="approveUser(user)" v-if="!user.is_approved">Approve
                                </PrimaryButton>
                                <DangerButton  @click="disapproveUser(user)">
                                    Disapprove
                                </DangerButton>
                                <DangerButton class="ml-2" @click="open = true">
                                    Archive User

                                </DangerButton>


                            </td>



                        </tr>
                    </tbody>
                </table>
            </div>
            <ConfirmationModal @close="open = false" :show="open">
                <template #title>
                    Are you sure?
                </template>

                <template #content>
                    Are you sure you want to archive this user  {{ all_users.name }}
                </template>

                <template #footer>
                    <DangerButton @click="archiveUser()" class="mr-2">
                        Archive User
                    </DangerButton>
                    <SecondaryButton @click="open = false">Cancel</SecondaryButton>
                </template>

            </ConfirmationModal>
        </Container>
    </AppLayout>




</template>