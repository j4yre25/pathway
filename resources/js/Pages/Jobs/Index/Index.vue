<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { router, usePage } from '@inertiajs/vue3'
import CreateJobs from './CreateJobs.vue'
import Container from '@/Components/Container.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import FormSection from '@/Components/FormSection.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
 import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import MyJobs from './MyJobs.vue'
import ManageJobs from './ManageJobs.vue'


const page = usePage()

const props = defineProps ({
    jobs: Array
})

console.log('User ID:', page.props);

const form = useForm({
    name: '',
    description: '',
    from_datetime: '',
    to_datetime: '',
    location: ''
});



</script>


<template>
    <AppLayout title ="Jobs">
        <template #header>
            Jobs
        </template>
        


        <Container class="py-4">


            <!-- <PrimaryButton @click="createJob()" class="">Post Job</PrimaryButton> --> 
            <div class="flex space-x-2">
                <div class="mt-8">
                    <Link :href="route('jobs.create', { user: page.props.auth.user.id })">
                        <PrimaryButton class="mr-2">Post Jobs</PrimaryButton>
                    </Link>
                </div>

                <div class="mt-8">
                    
                    <Link :href="route('jobs.manage', { user: page.props.auth.user.id })">
                        <PrimaryButton class="mr-2">Manage Posted Jobs</PrimaryButton>
                    </Link>
                </div>
            </div>


            <div class="mt-8">
                <MyJobs :jobs="jobs" />
            </div>

 
        </Container>

  

     

    </AppLayout>
 
</template>