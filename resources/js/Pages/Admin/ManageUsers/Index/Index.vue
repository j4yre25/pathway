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
const userToDelete = ref(null);

const deleteUser = (user) => {
    router.delete(route('admin.manage_users.delete', { user: user.id }));
};

const confirmDelete = (user) => {
    userToDelete.value = user;
    showModal.value = true;
};

const handleDelete = () => {
    if (userToDelete.value) {
        deleteUser (userToDelete.value);
        showModal.value = false;
        userToDelete.value = null; 
    }
};

</script>


<template>
    <AppLayout title="Manage Users">
        <template #header>
            Manage Users
        
        </template>
        
        <Container class="py-16">
            <div class="overflow-x-auto">
    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                <th class="border border-gray-200 px-6 py-3 text-left">ID</th>
                <th class="border border-gray-200 px-6 py-3 text-left">Name</th>
                <th class="border border-gray-200 px-6 py-3 text-left">Email</th>
                <th class="border border-gray-200 px-6 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
            <tr v-for="user in props.all_users" :key="user.id" class="border-b border-gray-200 hover:bg-gray-100">
                <td class="border border-gray-200 px-6 py-4">{{ user.id }}</td>
                <td class="border border-gray-200 px-6 py-4">{{ user.name }}</td>
                <td class="border border-gray-200 px-6 py-4">{{ user.email }}</td>
                <td class="border border-gray-200 px-6 py-4">
            

                    <!-- <Link :href="route('all_users.view', { user: user.id})">
                    View
                </Link>
              -->
              <Link :href="route('admin.manage_users.edit', { user: user.id })">
                     <PrimaryButton class ="mr-2">Edit</PrimaryButton>
                </Link>
                    <DangerButton @click="confirmDelete(user)" class="mr-2">
                                        Delete User
                    </DangerButton>
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