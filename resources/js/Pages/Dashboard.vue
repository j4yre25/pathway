<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

import { usePage } from "@inertiajs/vue3";
import { computed, watch, ref } from "vue";
import Welcome from "@/Components/Welcome.vue";
import Modal from "../Components/Modal.vue";
import CompanyDashboard from "../Pages/Company/CompanyDashboard.vue";
import { Inertia } from "@inertiajs/inertia";

const { props } = usePage();
const userNotApproved = computed(() => props.userNotApproved ?? false);
console.log(props.userNotApproved);
const showModal = ref(false);

if (userNotApproved.value) {
    showModal.value = true;
}

console.log("Show Modal:", showModal.value);

const handleLogout = () => {
    Inertia.visit(route("logout"), { method: "post" });
};
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7x1 mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <Modal v-if="showModal" :show="showModal">
                        <template #title> Account Not Approved </template>
                        <template #content>
                            <p>
                                Your account is not approved yet. Some features
                                may be disabled.
                            </p>
                        </template>
                        <template #footer>
                            <button
                                class="bg-blue-500 text-white px-4 py-2 rounded"
                                @click="handleLogout"
                            >
                                Okay
                            </button>
                        </template>
                    </Modal>
                    
                    <CompanyDashboard
                        v-if="props.auth.user.role === 'company'"
                        :summary="props.summary"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
