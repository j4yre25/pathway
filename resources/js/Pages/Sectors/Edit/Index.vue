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

            </Container>


        </template>

    </AppLayout>

</template>