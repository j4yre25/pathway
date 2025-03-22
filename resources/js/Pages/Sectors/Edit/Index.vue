<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Container from '@/Components/Container.vue';
    import UpdateForm from './UpdateForm.vue';
    import DeleteForm from './DeleteForm.vue';
    import SectionBorder from '@/Components/SectionBorder.vue';
   


    const props = defineProps({
        sector: Object
    })

</script>

<template>
    <AppLayout title="Edit Sector">
        <template #header>
           Edit #{{ sector.id }}


            <Container class="py-16">
          <UpdateForm :sector="sector" />

                <SectionBorder />

                      <DeleteForm :sector="sector" />

                      
                      <div class="mt-4">
                <div class="flex items-center justify-between">
                    <Link :href="route('categories', { sector: sector.id })">
                        <PrimaryButton>Manage Categories</PrimaryButton>
                    </Link>
                </div>
            </div>
          

            </Container>


        </template>

    </AppLayout>

</template>