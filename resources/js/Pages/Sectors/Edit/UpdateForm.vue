<script setup>
    import FormSection from '@/Components/FormSection.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import TextInput from '@/Components/TextInput.vue';
    import InputError from '@/Components/InputError.vue';
    import { useForm } from '@inertiajs/vue3';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import ActionMessage from '@/Components/ActionMessage.vue';
    import { Inertia } from '@inertiajs/inertia';



    const props = defineProps({
        sector: Object
    })

    const form = useForm({
        name: props.sector.name,

    })

    const submitForm = () => {
        form.put(route('sectors.update', {sector: props.sector.id}), {
            preserveScroll: true,

            onSuccess: () => {
            Inertia.visit(route('sectors.index'));
            },

            
        })
    }

</script>

<template>

 
                <FormSection @submitted="submitForm()">
                    <template #title>
                        Edit your Job
                    </template>

                    <template #description>
                        Form to edit your Job
                    </template>

                    <template #form>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel value="Job Name"/>
                                <TextInput v-model="form.name" type="text" class="block w-full"/>
                                <InputError :message="form.errors.name" />
                        </div>
                        
        
                    </template>
                    <template #actions>
                        <ActionMessage :on="form.recentlySuccessful" class="mr-2">
                            Saved.
                        </ActionMessage>
                        <PrimaryButton type ="submit">Save</PrimaryButton>
                    
                    </template>
                </FormSection>


</template>