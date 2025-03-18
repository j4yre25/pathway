<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3';
import ActionSection from '@/Components/ActionSection.vue';
import DangerButton from '@/Components/DangerButton.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    category: Object

})



const open = ref(false)

const deleteCategory= (r) => {
    router.delete(route('categories.delete', { category: props.category.id }));
};

</script>

<template>
    <ActionSection>
        <template #title>
            Delete Category
        </template>

        <template #description>
                This section to delete your category
        </template>

        <template #content>
            <DangerButton @click ="open = true">
                Delete Category

            </DangerButton>

            <ConfirmationModal @close="open = false" :show="open">
                <template #title>
                    Are you sure?
                </template>

                <template #content>
                        Are you sure you want to delete this category #{{ category.id }} {{ category.name }}
                </template>

                <template #footer>
                        <DangerButton @click="deleteCategory()" class="mr-2">
                            Delete Category
                        </DangerButton>
                        <SecondaryButton @click="open = false">Cancel</SecondaryButton>
                </template>

            </ConfirmationModal>
        </template>



    </ActionSection>


</template>