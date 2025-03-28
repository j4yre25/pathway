<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { usePage } from '@inertiajs/vue3';
import Container from '@/Components/Container.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import MyCategories from './MyCategories.vue';

const page = usePage();

const props = defineProps({
    sectors: Array, // Ensure the sectors prop is defined
});

// Access the sectors directly from props
const sectors = props.sectors; // This will be an array of sectors
console.log('Sectors:', sectors); // Debugging

</script>

<template>
    <AppLayout title="Categories">
        <template #header>
            Categories
        </template>

        <Container class="py-4">
            <div class="mt-8">
                <Link :href="route('categories.create')" class="mr-2">
                    <PrimaryButton>Add Categories</PrimaryButton>
                </Link>
            </div>

            <div class="mt-8">
                <div v-for="sector in sectors" :key="sector.id">
                    <h2>Sector: {{ sector.name }}</h2>
                    <MyCategories :categories="sector.categories" /> <!-- Pass categories for each sector -->
                </div>
            </div>
        </Container>
    </AppLayout>
</template>