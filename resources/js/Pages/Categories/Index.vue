<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { router, usePage } from '@inertiajs/vue3'

import Container from '@/Components/Container.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import FormSection from '@/Components/FormSection.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
 import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import MyCategories from './MyCategories.vue';



const page = usePage()

const props = defineProps ({
   categories: Array,
   sectors: Array
})

const sector = page.props.sector; // Access the single sector object
const categories = page.props.categories;
console.log('Sector:', sector); // Debugging
console.log('Categories:', categories); // Debugging

const form = useForm({
    name: '',

});

const createCategory = () => {
    form.post(route('categories.create', {  sector: sector.id  }), {
        onSuccess: () => {
            form.reset();
        }
    });
    
}

</script>


<template>
    <AppLayout title ="Categories">
        <template #header>
            Categories for {{ sector.name }}
        </template>
        


        <Container class="py-4">


            <!-- <PrimaryButton @click="createJob()" class="">Post Job</PrimaryButton> --> 
            <div class="mt-8">
                
                <Link :href="route('categories.create', { sector: sector.id })">
                    <PrimaryButton class="mr-2">Add Categories</PrimaryButton>
              </Link>
            </div>

            <div class="mt-8">
                <MyCategories :categories="categories" />


            </div>

 
        </Container>

  

     

    </AppLayout>
 
</template>