<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { computed } from 'vue';
import { MaskDirective } from 'vue-the-mask';
import { onMounted, ref } from 'vue';


// Determine the role based on the route
const role = window.location.pathname.includes('institution') ? 'institution' : 'company';

// Define the form with additional fields for user types
const form = useForm({
    email: '',
    password: '',
    password_confirmation: '',
    role: '', // Set the role dynamically
    company_name: '',
    company_street_address: '',
    company_brgy: '',
    company_city: '',
    company_province: '',
    company_zip_code: '',
    company_email: '',
    company_contact_number: '',
    company_telephone_number: '',
    company_hr_full_name: '',
    company_hr_gender: '',
    company_hr_dob: '',
    company_hr_contact_number: '',
    institution_type: '',
    institution_address: '',
    institution_contact_number: '',
    institution_president_last_name: '',
    institution_president_first_name: '',
    institution_career_officer_first_name: '',
});


onMounted(() => {
    const path = window.location.pathname;
    if (path.includes('institution')) {
        form.role = 'institution';
    } else if (path.includes('graduate')) {
        form.role = 'graduate';
    } else {
        form.role = 'company'; // Default to company if not specified
    }
});

// Format the company contact number
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
    // Determine the route based on the role
    let routeName;
    if (form.role === 'company') {
        routeName = 'register.company.store';
    } else if (form.role === 'institution') {
        routeName = 'register.institution.store';
    } else if (form.role === 'graduate') {
        routeName = 'register.graduate.store';
    } else {
        console.error('Unknown role:', form.role);
        return; 
    }

    form.post(route(routeName), {
        onFinish: () => {
            console.log("Form submission finished");
            console.log(form.errors);
            form.reset('password', 'password_confirmation');
        },
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

        <form @submit.prevent="submit" autocomplete="off">
            <!-- Company Fields -->
            <div v-if="form.role === 'company'" class="space-y-12 grid grid-cols-3">
                <!-- Company Basic Information -->
                <div class="col-span-1">
                    <h2 class="mt-12 text-lg font-semibold text-gray-900">Company Basic Information</h2>
                    <p class="text-sm text-gray-600">
                        Provide key details about your company.
                    </p>
                </div>

                <!-- Right Column: Username Input -->
                <div class="col-span-2">
                    <div class="flex items-center gap-1 col-span-6 sm:col-span-4">
                        <InputLabel for="company_name" value="Company Name" />
                        <span class="text-red-500">*</span>
                    </div>
                    <div>
                        <TextInput
                            id="company_name"
                            v-model="form.company_name"
                            type="text"
                            class="mt-1 mb-4 block w-full"
                            required />
                        <InputError class="mt-2" :message="form.errors.company_name" />
                    </div>

                    <!-- Company Address -->
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-2">
                            <div class="flex items-center gap-1">
                                <InputLabel for="company_street_address" value="Street Address" />
                                <span class="text-red-500">*</span>
                            </div>
                            <div>
                                <TextInput 
                                    id="company_street_address" 
                                    v-model="form.company_street_address" 
                                    type="text" 
                                    class="mt-1 mb-4 block w-full" 
                                    required />
                                <InputError class="mt-1" :message="form.errors.company_street_address" />
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center gap-1">
                                <InputLabel for="company_brgy" value="Barangay" />
                                <span class="text-red-500">*</span>
                            </div>
                            <div>
                                <TextInput 
                                    id="company_brgy" 
                                    v-model="form.company_brgy" 
                                    type="text" 
                                    class="mt-1 mb-4 block w-full" 
                                    required />
                                <InputError class="mt-1" :message="form.errors.company_brgy" />
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-5 gap-4">
                        <div class="col-span-2">
                            <div class="flex items-center gap-1">
                                <InputLabel for="company_city" value="City" />
                                <span class="text-red-500">*</span>
                            </div>
                            <div>
                                <TextInput 
                                    id="company_city" 
                                    v-model="form.company_city" 
                                    type="text" 
                                    class="mt-1 mb-4 block w-full" 
                                    required />
                                <InputError class="mt-1" :message="form.errors.company_city" />
                            </div>
                        </div>

                        <div class="col-span-2">
                            <div class="flex items-center gap-1">
                                <InputLabel for="company_province" value="Province" />
                                <span class="text-red-500">*</span>
                            </div>
                            <div>
                                <TextInput 
                                    id="company_province" 
                                    v-model="form.company_province" 
                                    type="text" 
                                    class="mt-1 mb-4 block w-full" 
                                    required />
                                <InputError class="mt-1" :message="form.errors.company_province" />
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center gap-1">
                                <InputLabel for="company_zip_code" value="ZIP Code" />
                                <span class="text-red-500">*</span>
                            </div>
                            <div>
                                <TextInput 
                                    id="company_zip_code" 
                                    v-model="form.company_zip_code" 
                                    type="text" 
                                    class="mt-1 mb-4 block w-full" 
                                    required />
                                <InputError class="mt-1" :message="form.errors.company_zip_code" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Institution Fields -->
            <div v-if="form.role === 'institution'" class="space-y-12 grid grid-cols-3">
                <div class="col-span-1">
                    <h2 class="mt-12 text-lg font-semibold text-gray-900">Institution Information</h2>
                    <p class="text-sm text-gray-600">
                        Provide key details about your institution.
                    </p>
                </div>

                <div class="col-span-2">
                    <div>
                        <div class="flex items-center gap-1">
                            <InputLabel for="institution_type" value="Institution Type" />
                            <span class="text-red-500">*</span>
                        </div>
                        <div>
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
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center gap-1">
                            <InputLabel for="institution_address" value="Institution Address" />
                            <span class="text-red-500">*</span>
                        </div>
                        <div>
                            <TextInput
                                id="institution_address"
                                v-model="form.institution_address"
                                type="text"
                                class="mt-1 mb-4 block w-full"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.institution_address" />
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center gap-1">
                            <InputLabel for="institution_contact_number" value="Institution Contact Number" />
                            <span class ="text-red-500">*</span>
                        </div>
                        <div>
                            <TextInput
                                id="institution_contact_number"
                                v-model="form.institution_contact_number"
                                type="text"
                                class="mt-1 mb-4 block w-full"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.institution_contact_number" />
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center gap-1">
                            <InputLabel for="institution_president_last_name" value="President Last Name" />
                            <span class="text-red-500">*</span>
                        </div>
                        <div>
                            <TextInput
                                id="institution_president_last_name"
                                v-model="form.institution_president_last_name"
                                type="text"
                                class="mt-1 mb-4 block w-full"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.institution_president_last_name" />
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center gap-1">
                            <InputLabel for="institution_president_first_name" value="President First Name" />
                            <span class="text-red-500">*</span>
                        </div>
                        <div>
                            <TextInput
                                id="institution_president_first_name"
                                v-model="form.institution_president_first_name"
                                type="text"
                                class="mt-1 mb-4 block w-full"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.institution_president_first_name" />
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center gap-1">
                            <InputLabel for="institution_career_officer_first_name" value="Career Officer First Name" />
                            <span class="text-red-500">*</span>
                        </div>
                        <div>
                            <TextInput
                                id="institution_career_officer_first_name"
                                v-model="form.institution_career_officer_first_name"
                                type="text"
                                class="mt-1 mb-4 block w-full"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.institution_career_officer_first_name" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 mt-8 border-t border-gray-200 pt-12">
                <!-- Company Contact Information -->
                <div class="col-span-1">
                    <h2 class="text-lg font-semibold text-gray-900">Company Contact Information</h2>
                    <p class="text-sm text-gray-600">
                        Provide the official contact details of your company.
                    </p>
                </div>

                <div class="col-span-2">
                    <div>
                        <div class="flex items-center gap-1">
                            <InputLabel for="company_email" value="Company Email Address" />
                            <span class="text-red-500">*</span>
                        </div>
                        <div>
                            <TextInput 
                                id="company_email"
                                v-model="form.company_email" 
                                type="email" 
                                class="mt-1 mb-4 block w-full" 
                                required/>
                            <InputError class="mt-1" :message="form.errors.company_email" />
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center gap-1">
                            <InputLabel for="company_contact_number" value="Company Contact Number" />  
                            <span class="text-red-500">*</span>
                        </div>
                        <div>
                            <TextInput
                                id="company_contact_number"
                                v-model="formattedContactNumber"
                                v-mask="'# (###) ###-####'"
                                type="tel"
                                class="mt-1 mb-4 block w-full"
                                required
                            />
                            <InputError class="mt-1" :message="form.errors.company_contact_number" />
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center gap-1">
                            <InputLabel for="company_telephone_number" value="Company Telephone Number" />
                        </div>
                        <TextInput
                            id="company_telephone_number"
                            v-model="form.company_telephone_number"
                            type="tel"
                            class="mt-1 mb-4 block w-full"
                        />
                        <InputError class="mt-1" :message="form.errors.company_telephone_number" />
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 mt-8 border-t border-gray-200 pt-12">
                <!-- Company HR Information -->
                <div class=" col-span-1">
                    <h2 class="text-lg font-semibold text-gray-900">Personal Information</h2>
                    <p class="text-sm text-gray-600"></p>
                </div>

                <div class="col-span-2">
                    <!-- Full Name -->
                    <div class="flex items-center gap-1">
                        <InputLabel for="company_hr_full_name" value="Full Name" />
                        <span class="text-red-500">*</span>
                    </div>
                    <div>
                        <TextInput id="company_hr_full_name" v-model="form.company_hr_full_name" type="text" class="mt-1 mb-4 block w-full" required />
                        <InputError class="mt-2" :message="form.errors.company_hr_full_name" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Gender -->
                        <div>
                            <div class="flex items-center gap-1">
                                <InputLabel for="company_hr_gender" value="Gender" />
                                <span class="text-red-500">*</span>
                            </div>
                            <div>
                                <select id="company_hr_gender" v-model="form.company_hr_gender" class="mt-1 mb-4 block w-full" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                <InputError class="" :message="form.errors.company_hr_gender" />
                            </div>
                        </div>
                        <!-- Date of Birth -->
                        <div>
                            <div class="flex items-center gap-1">
                                <InputLabel for="company_hr_dob" value="Date of Birth" />
                                <span class="text-red-500">*</span>
                            </div>
                            <div>
                                <TextInput 
                                    id="company_hr_dob" 
                                    v-model="form.company_hr_dob" 
                                    type="date" 
                                    class="mt-1 mb-4 block w-full" 
                                    required />
                                <InputError class="mt-2" :message="form.errors.company_hr_dob" />
                            </div>
                        </div>
                    </div>

                    <!-- HR Email -->
                    <div>
                        <div class="flex items-center gap-1">
                            <InputLabel for="email" value="Email Address" />
                            <span class="text-red-500">*</span>
                        </div>
                        <div>
                            <TextInput 
                                id="email" 
                                v-model="form.email" 
                                type="email" 
                                class="mt-1 mb-4 block w-full" 
                                required />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>
                    </div>

                    <!-- HR Contact Number -->
                    <div>
                        <div class="flex items-center gap-1">
                            <InputLabel for="company_hr_contact_number" value="Contact Number" />
                            <span class="text-red-500">*</span>
                        </div>
                        <div>
                            <TextInput 
                                id="company_hr_contact_number"
                                v-model="form.company_hr_contact_number"
                                v-mask="'# (###) ###-####'"
                                type="text"
                                class="mt-1 mb-4 block w-full"
                                required />
                            <InputError class="mt-2" :message="form.errors.company_hr_contact_number" />
                        </div>
                    </div>

                    <!-- Set Password -->
                    <h3 class="mt-6 mb-2 font-semibold">Set Password</h3>

                    <div>
                        <div class="flex items-center gap-1">
                            <InputLabel for="password" value="Password" />
                            <span class="text-red-500">*</span>
                        </div>
                        <div class="mb-2">
                            <TextInput 
                                id="password" 
                                v-model="form.password" 
                                type="password" 
                                class="mt-1 block w-full" 
                                required />
                            <InputError class="mt-1" :message="form.errors.password" />
                        </div>

                        <div class="flex items-center gap-1">
                            <InputLabel for="password_confirmation" value="Confirm Password" />
                            <span class="text-red-500">*</span>
                        </div>
                        <div class="mb-2">
                            <TextInput 
                                id="password_confirmation" 
                                v-model="form.password_confirmation" 
                                type="password" 
                                class="mt-1 mb-4 block w-full" 
                                required />
                            <InputError class="mt-1" :message="form.errors.password_confirmation" />
                        </div>
                    </div>
                </div>
            </div>

            < <div class="flex items-center justify-end mt-8 border-t border-gray-200 pt-12">
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