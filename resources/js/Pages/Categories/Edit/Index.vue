<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Container from '@/Components/Container.vue';
    import UpdateForm from './UpdateForm.vue';
    import DeleteForm from './DeleteForm.vue';
    import SectionBorder from '@/Components/SectionBorder.vue';
   


    const props = defineProps({
        category: Object
    })

</script>

<template>
    <AppLayout title="Edit Category">
        <template #header>
           Edit #{{ category.id }}


            <Container class="py-16">
          <UpdateForm :category="category" />

                <SectionBorder />

                      <DeleteForm :category ="category" />

            </Container>


        </template>

    </AppLayout>

</template>