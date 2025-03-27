<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { computed } from 'vue';
import { mask } from 'vue-the-mask';


// Define the form with additional fields for user types
const form = useForm({
    email: '',
    password: '',
    password_confirmation: '',
    role: '', 
  
    company_name: '',
    company_address: '',
    company_sector: '',
    company_category: '',
    company_contact_number: '',
    company_hr_last_name: '',
    company_hr_first_name: '',
    company_hr_middle_initial: '',


    terms: false,
});


const formattedContactNumber = computed({
    get: () => {
        let rawNumber = form.company_contact_number.replace(/\D/g, ""); // Remove non-numeric characters

        // Ensure only the first 10 digits are considered
        if (rawNumber.length > 10) {
            rawNumber = rawNumber.slice(0, 10);
        }

        // Ensure that the displayed format is always "+63 XXX XXX XXXX"
        return `+63 ${rawNumber.replace(/(\d{3})(\d{3})(\d{4})/, "$1 $2 $3")}`.trim();
    },
    set: (value) => {
        // Remove non-numeric characters except for numbers
        let rawValue = value.replace(/\D/g, "");

        // Ensure only the last 10 digits are stored
        if (rawValue.startsWith("63")) {
            rawValue = rawValue.slice(2);
        }

        if (rawValue.startsWith("0")) {
            rawValue = rawValue.slice(1);
        }

        if (rawValue.length > 10) {
            rawValue = rawValue.slice(0, 10);
        }

        form.company_contact_number = rawValue;
    },
});



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

            <form @submit.prevent="submit" >
                <div class="space-y-12 grid grid-cols-2 " >
                    <!-- Company Basic Information -->
                
                    
                    <div>
                        <h2 class=" mt-12 text-lg font-semibold text-gray-900">Company Basic Information</h2>
                        <p class="text-sm text-gray-600">
                        This information will be displayed publicly so be careful what you share.
                        </p>
                    </div>

                    <!-- Right Column: Username Input -->
                    <div>
                        <div class=" col-span-6 sm:col-span-4">
                            <InputLabel for="company_name" value="Company Name" />
                            <TextInput
                                id="company_name"
                                v-model="form.company_name"
                                type="text"
                                class="mt-1 mb-4 block w-full"
                                required />
                            <InputError class="mt-2" :message="form.errors.company_name" />
                        </div>
                        
                        <!-- Company Address -->
                        <div class = "grid grid-cols-3 gap-4" >
                            <div class="col-span-2">
                                <InputLabel for="company_street_address" value="Street Address" />
                                <TextInput 
                                    id="company_street_address" 
                                    v-model="form.company_street_address" 
                                    type="text" 
                                    class="mt-1 mb-4 block w-full" 
                                    required />
                                <InputError class="mt-1" :message="form.errors.company_street_address" />
                            </div>

                            <div>
                                <InputLabel for="company_brgy" value="Barangay" />
                                <TextInput 
                                    id="company_brgy" 
                                    v-model="form.company_brgy" 
                                    type="text" 
                                    class="mt-1 mb-4 block w-full" 
                                    required />
                                <InputError class="mt-1" :message="form.errors.company_brgy" />
                            </div>
                        </div>
                        
                        <div class = "grid grid-cols-5 gap-4" >
                                <div class="col-span-2">
                                <InputLabel for="company_city" value="City" />
                                <TextInput 
                                    id="company_city" 
                                    v-model="form.company_city" 
                                    type="text" 
                                    class="mt-1 mb-4 block w-full" 
                                    required />
                                <InputError class="mt-1" :message="form.errors.company_city" />
                            </div>
                            
                            <div class="col-span-2">
                                <InputLabel for="company_province" value="Province" />
                                <TextInput 
                                    id="company_province" 
                                    v-model="form.company_province" 
                                    type="text" 
                                    class="mt-1 mb-4 block w-full" 
                                    required />
                                <InputError class="mt-1" :message="form.errors.company_province" />
                            </div>

                            
                            <div>
                                <InputLabel for="company_zip_code" value="ZIP Code" />
                                <TextInput 
                                    id="company_zip_code" 
                                    v-model="form.company_zip_code" 
                                    type="text" 
                                    class="mt-1 mb-4 block w-full" 
                                    required />
                                <InputError class="mt-1" :message="form.errors.company_zip_code" />
                            </div>
                        </div>

                        <!-- Sector & Category -->
                        <div>
                            <InputLabel for="company_sector" value="Company Sector" />
                            <TextInput
                                id="company_sector"
                                v-model="form.company_sector"
                                type="text"
                                class="mt-1 mb-4 block w-full"
                                required
                            />
                            <InputError class="mt-1" :message="form.errors.company_sector" />
                        </div>

                        <div>
                            <InputLabel for="company_category" value="Company Category" />
                            <TextInput
                                id="company_category"
                                v-model="form.company_category"
                                type="text"
                                class="mt-1 mb-4 block w-full"
                                required
                            />
                            <InputError class="mt-1" :message="form.errors.company_category" />
                        </div>
                    </div>
                </div>

                <div class=" grid grid-cols-2  mt-8 border-t border-gray-200 pt-12">
                     <!-- Company Contact Information -->
                     <div>
                        <h2 class="text-lg font-semibold text-gray-900">Company Contact Information</h2>
                        <p class="text-sm text-gray-600">
                        This information will be displayed publicly so be careful what you share.
                        </p>
                    </div>

                    <div>
                        <div>
                            <InputLabel for="email" value="Email" />
                            <TextInput 
                                id="email"
                                v-model="form.email" 
                                type="email" 
                                class="mt-1 mb-4 block w-full" 
                                required/>
                            <InputError class="mt-1" :message="form.errors.email" />
                        </div>

                        <div>
                            <InputLabel for="company_contact_number" value="Company Contact Number" />
                            <TextInput
                                id="company_contact_number"
                                v-model="formattedContactNumber"
                                v-mask="'# (###) ###-####'"
                                type="text"
                                class="mt-1 mb-4 block w-full"
                                required
                            />
                            <InputError class="mt-1" :message="form.errors.company_contact_number" />
                        </div>


                        <div>
                            <InputLabel for="company_telephone_number" value="Company Telephone Number" />
                            <TextInput
                                id="company_telephone_number"
                                v-model="form.company_telephone_number"
                                type="text"
                                class="mt-1 mb-4 block w-full"
                            />
                            <InputError class="mt-1" :message="form.errors.company_telephone_number" />
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2  mt-8 border-t border-gray-200 pt-12">
                     <!-- Company HR Information -->
                     <div>
                        <h2 class="text-lg font-semibold text-gray-900">Human Resoure Officer Information</h2>
                        <p class="text-sm text-gray-600">
                        This information will be displayed publicly so be careful what you share.
                        </p>
                    </div>

                    <div>
                        <!-- Full Name -->
                        <div>
                            <InputLabel for="company_hr_full_name" value="Full Name" />
                            <TextInput id="company_hr_full_name" v-model="form.company_hr_full_name" type="text" class="mt-1 mb-4 block w-full" required />
                            <InputError class="mt-2" :message="form.errors.company_hr_full_name" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Gender -->
                            <div>
                                <InputLabel for="company_hr_gender" value="Gender" />
                                <select id="company_hr_gender" v-model="form.company_hr_gender" class="mt-1 mb-4 block w-full">
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.company_hr_gender" />
                            </div>

                            <!-- Date of Birth -->
                            <div>
                                <InputLabel for="company_hr_dob" value="Date of Birth" />
                                <TextInput 
                                    id="company_hr_dob" 
                                    v-model="form.company_hr_dob" 
                                    type="date" 
                                    class="mt-1 mb-4 block w-full" 
                                    required />
                                <InputError class="mt-2" :message="form.errors.company_hr_dob" />
                            </div>
                        </div>  

                        <!-- HR Email -->
                        <div>
                            <InputLabel for="company_hr_email" value="Email Address" />
                            <TextInput 
                                id="company_hr_email" 
                                v-model="form.company_hr_email" 
                                type="email" 
                                class="mt-1 mb-4 block w-full" 
                                required />
                            <InputError class="mt-2" :message="form.errors.company_hr_email" />
                        </div>

                        <!-- HR Contact Number -->
                        <div>
                            <InputLabel for="company_hr_contact_number" value="Contact Number" />
                            <TextInput 
                                id="company_hr_contact_number" 
                                v-model="form.company_hr_contact_number" 
                                type="text" class="mt-1 mb-4 block w-full" 
                                required />
                            <InputError class="mt-2" :message="form.errors.company_hr_contact_number" />
                        </div>

                        <!-- Set Password -->
                        <h3 class="mt-6 font-semibold">Set Password</h3>

                        <div>
                            <InputLabel for="password" value="Password" />
                            <TextInput 
                                id="password" 
                                v-model="form.password" 
                                type="password" 
                                class="mt-1 block w-full" 
                                required />
                            <InputError class="mt-1" :message="form.errors.password" />
                        </div>

                        <div>
                            <InputLabel for="password_confirmation" value="Confirm Password" />
                            <TextInput 
                                id="password_confirmation" 
                                v-model="form.password_confirmation" 
                                type="password" 
                                class="mt-1 mb-4 block w-full" 
                                required/>
                            <InputError class="mt-1" :message="form.errors.password_confirmation" />
                        </div>
                    </div>
                </div>

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

                <div class="flex items-center justify-end mt-8 border-t border-gray-200 pt-12">
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