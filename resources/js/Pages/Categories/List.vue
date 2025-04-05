<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Container.vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    categories: Array,
    sectors: Array
});

console.log('Categories:', props.categories);
</script>

<template>
    <AppLayout title="Manage Users">
        <template #header>
            List of Categories

        </template>

        <Container class="py-8">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                            <th class="border border-gray-200 px-6 py-3 text-left">Name</th>
                            <th class="border border-gray-200 px-6 py-3 text-left">Posted By</th>

                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        <tr v-for="category in categories" :key="category.id"
                            class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="border border-gray-200 px-6 py-4">{{ category.name }}</td>
                            <td class="border border-gray-200 px-6 py-4">{{ category.user ? category.user.peso_first_name : 'Unknown' }}</td>


                        </tr>
                    </tbody>
                </table>
            </div>
        </Container>
    </AppLayout>
</template>