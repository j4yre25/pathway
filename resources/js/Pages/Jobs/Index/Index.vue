<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { router, usePage } from '@inertiajs/vue3'
import MyJobs from './MyJobs.vue'
import Container from '@/Components/Container.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import FormSection from '@/Components/FormSection.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
 import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';


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

const createJob = () => {
    form.post(route('jobs.create', { user: page.props.auth.user.id }), {
        onSuccess: () => {
            form.reset();
        }
    });
    
}

</script>


<template>
    <AppLayout title ="Jobs">
        <template #header>
            Jobs
        </template>
        


        <Container class="py-16">


            <!-- <PrimaryButton @click="createJob()" class="">Post Job</PrimaryButton> --> 
            <div class="mt-8">
                <FormSection @submitted="createJob()">
                    <template #title>
                        Post a New Job
                    </template>

                    <template #description>
                        Fill in the details below to post a new job.
                    </template>

                    <template #form>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="name" value="Job Name" />
                            <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" />
                            <InputError :message="form.errors.name" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="description" value="Job Description" />
                            <TextInput id="description" v-model="form.description" type="text" class="mt-1 block w-full" />
                            <InputError :message="form.errors.description" class="mt-2" />
                        </div>

                        <!-- <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="from_datetime" value="From Date and Time" />
                            <TextInput id="from_datetime" v-model="form.from_datetime" type="datetime-local" class="mt-1 block w-full" />
                            <InputError :message="form.errors.from_datetime" class="mt-2" />
                        </div> -->

                        <!-- <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="to_datetime" value="To Date and Time" />
                            <TextInput id="to_datetime" v-model="form.to_datetime" type="datetime-local" class="mt-1 block w-full" />
                            <InputError :message="form.errors.to_datetime" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="location" value="Location" />
                            <TextInput id="location" v-model="form.location" type="text" class="mt-1 block w-full" />
                            <InputError :message="form.errors.location" class="mt-2" />
                        </div> -->
                    </template>
                    <template #actions>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Post Job
                        </PrimaryButton>
                    </template>
                </FormSection>


            </div>
 
        </Container>

  

     

    </AppLayout>
 
</template>