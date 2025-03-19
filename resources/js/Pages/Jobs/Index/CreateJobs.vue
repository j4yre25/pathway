<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { router, usePage, useForm  } from '@inertiajs/vue3'
import Container from '@/Components/Container.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import FormSection from '@/Components/FormSection.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { ref } from 'vue';


const page = usePage()

const props = defineProps ({
    jobs: Array
})

console.log('User ID:', page.props);

const form = useForm({
    job_title: '', 
    location: '',
    vacancy: '',
    salary: '',
    job_type: '',
    experience_level: '',
    description: '',
    skills: [],
});

const newSkill = ref('');

const addSkill = () => {
    if (newSkill.value.trim() !== '') {
        form.skills.push(newSkill.value.trim());
        newSkill.value = '';
    }
};

const removeSkill = (index) => {
    form.skills.splice(index, 1);
};


const createJob = () => {
    console.log('Form data:', form);
    form.post(route('jobs.store', { user: page.props.auth.user.id }), {
        onSuccess: () => {
            // form.reset();
            router.visit(route('jobs', { user: page.props.auth.user.id }));
        },
        onError: (errors) => {
            console.log('Validation errors:', errors);
        }
    });

    console.log('Route:', route('jobs.store', { user: page.props.auth.user.id }));
}   

</script>


<template>
   <AppLayout>
        <template >
            Jobs
        </template>
        


        <Container class="py-15">


            <!-- <PrimaryButton @click="createJob()" class="">Post Job</PrimaryButton> --> 
            <div class="mt-8">
                <FormSection @submitted="createJob()">
                    <template #title>
                        Post a New Job
                    </template>

                    <template #description>
                        Fill in the detailcs below to post a new job.
                    </template>

                    <template #form class="py-4">
                        <div class=" col-span-6 sm:col-span-4">
                            <InputLabel for="job_title" value="Job Title" class="mb-2"/>
                            <TextInput id="job_title" v-model="form.job_title" type="text" placeholder="Job Title" class="mt-1 mb-5 block w-full p-2 border rounded-lg" required />
                            <InputError :message="form.errors.job_title" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-1">
                                <InputLabel for="location" value="Job Location" class="mb-2"/>
                                <TextInput id="location" v-model="form.location" type="text" placeholder="Job Location" class="mt-1 mb-2 p-2 border rounded-lg" required />
                                <InputError :message="form.errors.location" class="mt-2" />
                            </div>

                            <div class="col-span-1">
                                <InputLabel for="vacancy" value="No. of Vacancies" class="mb-2" />
                                <TextInput id="vacancy" v-model="form.vacancy" type="number" placeholder="No. of Vacancies" class="mt-1 mb-2 p-2 border rounded-lg" required />
                                <InputError :message="form.errors.vacancy" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div class="col-span-1">
                                <InputLabel for="salary" value="Salary Range" class="mb-2" />
                                <TextInput id="salary" v-model="form.salary" type="text" placeholder="Salary Range" class="mt-1 mb-2    p-2 border rounded-lg" />
                                <InputError :message="form.errors.salary" class="mt-2" />
                            </div>

                            <div class="col-span-1">
                                <InputLabel for="job_type" value="Job Type" class="mb-2"/>
                                <select v-model="form.job_type" id="job_type" class="p-2 border rounded-lg">
                                    <option value="">Select Employment Type</option>
                                    <option value="full-time">Full-Time</option>
                                    <option value="part-time">Part-Time</option>
                                </select>
                                <InputError :message="form.errors.job_type" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div class="col-span-1">
                                <InputLabel for="experience_level" value="Experience Level" class="mb-2" />
                                <select v-model="form.experience_level" id="experience_level" class="p-2 border rounded-lg">
                                    <option value="">Select Experience Level</option>
                                    <option value="entry-level">Entry-level</option>
                                    <option value="intermediate">Intermediate</option>
                                    <option value="mid-level">Mid-level</option>
                                    <option value="senior-executive">Senior/Executive-level</option>
                                </select>
                                <InputError :message="form.errors.experience_level" class="mt-2" />
                            </div>
                        </div>

                        <div class="col-span-6 sm:col-span-4 mt-5" >
                            <InputLabel for="description" value="Job Description" />
                            <textarea id="description" v-model="form.description" placeholder="Job Description" class="w-full p-2 border rounded-lg h-28 mt-2" required></textarea>
                            <InputError :message="form.errors.description" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <InputLabel value="Skills" />
                            <div class="flex flex-wrap gap-2 mt-2">
                                <span v-for="(skill, index) in form.skills" :key="index" class="px-3 py-1 bg-gray-200 rounded-full flex items-center">
                                    {{ skill }}
                                    <button @click="removeSkill(index)" class="ml-2 text-red-500 font-bold">×</button>
                                </span>
                            </div>
                            <div class="mt-2 flex">
                                <TextInput v-model="newSkill" type="text" placeholder="Add a skill" class="p-2 border rounded-lg w-full" />
                                <button @click="addSkill" type="button" class="ml-2 px-4 py-2 bg-blue-600 text-white rounded-lg">+</button>
                                <InputError :message="form.errors.newSkill" class="mt-2" />
                            </div>
                        </div>
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
