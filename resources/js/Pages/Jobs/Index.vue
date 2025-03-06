<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { router, usePage } from '@inertiajs/vue3'
import MyJobs from './MyJobs.vue'
import Container from '@/Components/Container.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'


const page = usePage()

const props = defineProps ({
    jobs: Array
})

const createJob = () => {
    router.post(route('jobs.create', { user: page.props.auth.user.id }))
}

</script>


<template>
    <AppLayout title ="Jobs">
        <template #header>
            Jobs
        </template>

        

        <Container class="py-16">
            <MyJobs :jobs="jobs" />

            <div class="mt-8">
            <PrimaryButton @click="createJob" class="">Post Job</PrimaryButton>
       </div>

        </Container>


     

    </AppLayout>
 
</template>