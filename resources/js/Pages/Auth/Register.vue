<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';

// Define the form with additional fields for user types
const form = useForm({
    email: '',
    password: '',
    password_confirmation: '',
    role: '', // Add role field
    // Graduate-specific fields
    graduate_specific_field: '',
    // Company-specific fields
    company_name: '',
    company_address: '',
    company_sector: '',
    company_category: '',
    company_contact_number: '',
    company_hr_last_name: '',
    company_hr_first_name: '',
    company_hr_middle_initial: '',
    // Institution-specific fields
    institution_type: '',
    institution_address: '',
    institution_contact_number: '',
    institution_president_last_name: '',
    institution_president_first_name: '',
    institution_career_officer_first_name: '',
    // Graduate-specific fields
    graduate_school_graduated_from: '',
    graduate_program_completed: '',
    graduate_year_graduated: '',
    graduate_skills: '',
    graduate_first_name: '',
    graduate_last_name: '',

    terms: false,
});

// Track the selected user type


// Handle form submission
const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
        onSuccess: () => {
            Inertia.visit(route('login'));
        },
    });
};
</script>

<template>
    <Head title="Register" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <form @submit.prevent="submit">

            <div class="mt-4">
                <InputLabel for="role" value="User Type" />
                <select
                    id="role"
                    v-model="form.role"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    required
                >
                    <option value="">Select User Type</option>
                    <option value="graduate">Graduate</option>
                    <option value="company">Company</option>
                    <option value="institution">Institution</option>
                </select>
                <InputError class="mt-2" :message="form.errors.role" />
            </div>

            <!-- Dynamic Fields Based on User Type -->
            <!-- Graduate Fields -->
            <div v-if="form.role === 'graduate'" class="mt-4">
                <!-- Graduate First Name -->
                <InputLabel for="graduate_first_name" value="Graduate First Name" />
                <TextInput
                    id="graduate_first_name"
                    v-model="form.graduate_first_name"
                    type="text"
                    class="mt-1 block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.graduate_first_name" />

                <!-- Graduate Last Name -->
                <InputLabel for="graduate_last_name" value="Graduate Last Name" />
                <TextInput
                    id="graduate_last_name"
                    v-model="form.graduate_last_name"
                    type="text"
                    class="mt-1 block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.graduate_last_name" />

            


                <div class="mt-4">
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    v-model="form.email" 
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autocomplete="email"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>


            <div class="mt-4">
            <InputLabel for="password" value="Password" />
            <TextInput
                id="password"
                v-model="form.password"
                type="password"
                class="mt-1 block w-full"
                required
                autocomplete="new-password"
            />
            <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <div class="mt-4">
            <InputLabel for="password_confirmation" value="Confirm Password" />
            <TextInput
                id="password_confirmation"
                v-model="form.password_confirmation"
                type="password"
                class="mt-1 block w-full"
                required
                autocomplete="new-password"
            />
            <InputError class="mt-2" :message="form.errors.password_confirmation" />
        </div>
            
                <!-- Graduate School Graduated From -->
                <InputLabel for="graduate_school_graduated_from" value="School Graduated From" />
                <TextInput
                    id="graduate_school_graduated_from"
                    v-model="form.graduate_school_graduated_from"
                    type="text"
                    class="mt-1 block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.graduate_school_graduated_from" />

                <!-- Program Completed -->
                <InputLabel for="graduate_program_completed" value="Program Completed" />
                <TextInput
                    id="graduate_program_completed"
                    v-model="form.graduate_program_completed"
                    type="text"
                    class="mt-1 block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.graduate_program_completed" />

                <!-- Year Graduated -->
                <InputLabel for="graduate_year_graduated" value="Year Graduated" />
                <TextInput
                    id="graduate_year_graduated"
                    v-model="form.graduate_year_graduated"
                    type="date" 
                    class="mt-1 block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.graduate_year_graduated" />

                <!-- Skills -->
                <InputLabel for="graduate_skills" value="Skills" />
                <TextInput
                    id="graduate_skills"
                    v-model="form.graduate_skills"
                    type="text"
                    class="mt-1 block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.graduate_skills" />
            </div>

            <!-- Company Fields -->
            <div v-if="form.role === 'company'" class="mt-4">
                <InputLabel for="company_name" value="Company Name" />
                <TextInput
                    id="company_name"
                    v-model="form.company_name"
                    type="text"
                    class="mt-1 mb-4 block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.company_name" />
                
                <div class="mt-4">
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    v-model="form.email" 
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autocomplete="email"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Password" />
                    <TextInput
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full"
                        required
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password_confirmation" value="Confirm Password" />
                    <TextInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        required
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>

                <div class="mt-4">
                <InputLabel for="company_address" value="Company Address" />
                <TextInput
                    id="company_address"
                    v-model="form.company_address"
                    type="text"
                    class="mt-1 mb-4  block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.company_address" />
            </div>

            <div class="mt-4">
                <InputLabel for="company_sector" value="Company Sector" />
                <TextInput
                    id="company_sector"
                    v-model="form.company_sector"
                    type="text"
                    class="mt-1 mb-4  block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.company_sector" />
            </div>
    

                <InputLabel for="company_category" value="Company Category" />
                <TextInput
                    id="company_category"
                    v-model="form.company_category"
                    type="text"
                    class="mt-1 mb-4  block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.company_category" />


             
              

                <InputLabel for="company_contact_number" value="Company Contact Number" />
                <TextInput
                    id="company_contact_number"
                    v-model="form.company_contact_number"
                    type="text"
                    class="mt-1 mb-4 block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.company_contact_number" />

                <InputLabel for="company_hr_last_name" value="HR Last Name" />
                <TextInput
                    id="company_hr_last_name"
                    v-model="form.company_hr_last_name"
                    type="text"
                    class="mt-1 mb-4  block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.company_hr_last_name" />

                <InputLabel for="company_hr_first_name" value="HR First Name" />
                <TextInput
                    id="company_hr_first_name"
                    v-model="form.company_hr_first_name"
                    type="text"
                    class="mt-1 mb-4  block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.company_hr_first_name" />

                <InputLabel for="company_hr_middle_initial" value="HR Middle Initial" />
                <TextInput
                    id="company_hr_middle_initial"
                    v-model="form.company_hr_middle_initial"
                    type="text"
                    class="mt-1 mb-4  block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.company_hr_middle_initial" />
            </div>

            <!-- Institution Fields -->
            <div v-if="form.role === 'institution'" class="mt-4">
                <InputLabel for="institution_type" value="Institution Type" />
                <select
                    id="institution_type"
                    v-model="form.institution_type"
                    class="mt-1 mb-4 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    required
                >
                    <option value="college">College</option>
                    <option value="university">University</option>
                    <option value="institution">Institution</option>
                </select>
                <InputError class="mt-2" :message="form.errors.institution_type" />
          <div class="mt-4">
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    v-model="form.email" 
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autocomplete="email"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Password" />
                    <TextInput
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full"
                        required
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password_confirmation" value="Confirm Password" />
                    <TextInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        required
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>
                
                <div class="mt-4">
                <InputLabel for="institution_address" value="Institution Address" />
                <TextInput
                    id="institution_address"
                    v-model="form.institution_address"
                    type="text"
                    class="mt-1 mb-4 block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.institution_address" />
            </div>

               
                <InputLabel for="institution_contact_number" value="Institution Contact Number" />
                <TextInput
                    id="institution_contact_number"
                    v-model="form.institution_contact_number"
                    type="text"
                    class="mt-1 mb-4 block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.institution_contact_number" />

                <InputLabel for="institution_president_last_name" value="President Last Name" />
                <TextInput
                    id="institution_president_last_name"
                    v-model="form.institution_president_last_name"
                    type="text"
                    class="mt-1 mb-4 block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.institution_president_last_name" />

                <InputLabel for="institution_president_first_name" value="President First Name" />
                <TextInput
                    id="institution_president_first_name"
                    v-model="form.institution_president_first_name"
                    type="text"
                    class="mt-1 mb-4 block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.institution_president_first_name" />

                <InputLabel for="institution_career_officer_first_name" value="Career Officer First Name" />
                <TextInput
                    id="institution_career_officer_first_name"
                    v-model="form.institution_career_officer_first_name"
                    type="text"
                    class="mt-1 mb-4 block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.institution_career_officer_first_name" />
            </div>
    

            

            <!-- User Type Selection -->
            

            <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                <InputLabel for="terms">
                    <div class="flex items-center">
                        <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />
                        <div class="ms-2">
                            I agree to the <a target="_blank" :href="route('terms.show')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Terms of Service</a> and <a target="_blank" :href="route('policy.show')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Privacy Policy</a>
                        </div>
                    </div>
                    <InputError class="mt-2" :message="form.errors.terms" />
                </InputLabel>
            </div>

            <div class="flex items-center justify-end mt-8">
                <Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Already registered?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>