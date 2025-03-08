<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Container from '@/Components/Container.vue';
    // import UpdateForm from './UpdateForm.vue';
    // import DeleteForm from './DeleteForm.vue';
    import SectionBorder from '@/Components/SectionBorder.vue';
   


    const props = defineProps({
        user: Object
    })

</script>

<template>
    <AppLayout title="Edit User">
        <template #header>
           Edit #{{ user.id }}


            <Container class="py-16">
                <!-- <UpdateForm :user="user" />

                <SectionBorder />

                <DeleteForm :user="user" /> -->

            </Container>


        </template>

    </AppLayout>

</template>