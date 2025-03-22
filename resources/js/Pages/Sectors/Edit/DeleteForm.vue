<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3';
import ActionSection from '@/Components/ActionSection.vue';
import DangerButton from '@/Components/DangerButton.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    sector: Object

})



const open = ref(false)

const deleteSector= (r) => {
    router.delete(route('sectors.delete', { sector: props.sector.id }));
};

</script>

<template>
    <ActionSection>
        <template #title>
            Delete Sector
        </template>

        <template #description>
                This section to delete your sector
        </template>

        <template #content>
            <DangerButton @click ="open = true">
                Delete Sector

            </DangerButton>

            <div v-for="sector in sectors" :key="sector.id" class="mt-4">
                    <div class="flex items-center justify-between">
                        <!-- <span>{{ sector.name }}</span> -->
                        <Link :href="route('categories', { sector: sector.id })">
                            <PrimaryButton>Manage Categories</PrimaryButton>
                        </Link>
                    </div>
                </div>

            
         

            <ConfirmationModal @close="open = false" :show="open">
                <template #title>
                    Are you sure?
                </template>

                <template #content>
                        Are you sure you want to delete this sector #{{ sector.id }} {{ sector.name }}
                </template>

                <template #footer>
                        <DangerButton @click="deleteSector()" class="mr-2">
                            Delete Sector
                        </DangerButton>
                        <SecondaryButton @click="open = false">Cancel</SecondaryButton>
                        
                </template>

            </ConfirmationModal>
        </template>



    </ActionSection>


</template>