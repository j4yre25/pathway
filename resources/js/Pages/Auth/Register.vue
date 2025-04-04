<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import LoginCard from '@/Components/LoginCard.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { onMounted, ref, computed } from 'vue';
import { defineProps } from 'vue';
import Modal from '@/Components/Modal.vue';
import { Inertia } from '@inertiajs/inertia';


const props = defineProps ({
    insti_users: Array,
    school_year: Array,
 
})

console.log(props)

const isPasswordFocused = ref(false);
const showModal = ref(false); 

// Define the form with additional fields for user types
const form = useForm({
    email: '',
    password: '',
    password_confirmation: '',
    role: '', // Set the role dynamically
    gender: '',
    dob: '',
    contact_number: '',
    telephone_number: '',
    company_name: '',
    company_street_address: '',
    company_brgy: '',
    company_city: '',
    company_province: '',
    company_zip_code: '',
    company_email: '',
    company_contact_number: '',
    company_hr_first_name: '',
    company_hr_last_name: '',
    institution_type: '',
    institution_name: '',
    institution_address: '',
    institution_president_last_name: '',
    institution_president_first_name: '',
    institution_career_officer_first_name: '',
    institution_career_officer_last_name: '',
    graduate_school_graduated_from: '',
    graduate_program_completed: '',
    graduate_year_graduated: '',
    graduate_first_name: '',
    graduate_middle_initial: '',
    graduate_last_name: '',
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
    console.log('Current Role:', form.role);
});

console.log(form.role);

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

// Update 04/04
const passwordCriteria = computed(() => {
    const password = form.password;
    return {
        length: password.length >= 8, // Minimum 8 characters
        uppercaseLowercase: /[a-z]/.test(password) && /[A-Z]/.test(password), // Upper & lower case
        letter: /[a-zA-Z]/.test(password), // At least one letter
        number: /\d/.test(password), // At least one number
        symbol: /[@$!%*?&.]/.test(password), // At least one special character
    };
});

// Handle form submission
const submit = () => {
    
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

    console.log('Form Data:', form);


    form.post(route(routeName), {
        onFinish: () => {
            console.log("Form submission finished");
            console.log(form.errors);
            form.reset('password', 'password_confirmation');
        },
        onSuccess: () => {
          
                    showModal.value = true;
                    console.log("showModal:", showModal.value);

            }


            });
};

const   redirectToLogin = () => {
    Inertia.visit(route('login')); 
};

</script>

<template>
    <Head title="Register" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>


        <!-- UPDATE 04/04 Putting form inside the template for registrtion form layout -->
        <template #registerForm>
                
            <form @submit.prevent="submit" autocomplete="off">
                <!-- Company Fields -->
                <div v-if="form.role === 'company'">
                    <div class="space-y-12 grid grid-cols-3">
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
                                    <InputLabel for="telephone_number" value="Company Telephone Number" />
                                </div>
                                <TextInput
                                    id="telephone_number"
                                    v-model="form.telephone_number"
                                    type="tel"
                                    class="mt-1 mb-4 block w-full"
                                />
                                <InputError class="mt-1" :message="form.errors.telephone_number" />
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

                            <div class="grid grid-cols-2 gap-4">
                               <div> <!-- First Name -->
                                    <div class="flex items-center gap-1">
                                        <InputLabel for="company_hr_first_name" value= "First Name" />
                                        <span class="text-red-500">*</span>
                                    </div>
                                    <div>
                                        <TextInput id="company_hr_first_name" v-model="form.company_hr_first_name" type="text" class="mt-1 mb-4 block w-full" required />
                                        <InputError class="mt-2" :message="form.errors.company_hr_first_name" />
                                    </div>
                               </div>
                               <div> <!-- First Name -->
                                    <div class="flex items-center gap-1">
                                        <InputLabel for="company_hr_last_name" value= "Last Name" />
                                        <span class="text-red-500">*</span>
                                    </div>
                                    <div>
                                        <TextInput id="company_hr_last_name" v-model="form.company_hr_last_name" type="text" class="mt-1 mb-4 block w-full" required />
                                        <InputError class="mt-2" :message="form.errors.company_hr_last_name" />
                                    </div>
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
                                <InputLabel for="institution_name" value="College/University/Institution Name" />
                                <span class="text-red-500">*</span>
                            </div>
                            <div>
                                <TextInput
                                    id="institution_name"
                                    v-model="form.institution_name"
                                    type="text"
                                    class="mt-1 mb-4 block w-full"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.institution_name" />
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

                <!-- Graduate Fields -->
                <div v-if="form.role === 'graduate'" class="mt-4">
                    <!-- Graduate First Name -->
                    <InputLabel for="graduate_first_name" value="Graduate First Name" />
                    <TextInput
                        id="graduate_first_name"
                        v-model="form.graduate_first_name"
                        type="text"
                        class="mt-1 block w-full"
                        required/>
                    <InputError class="mt-2" :message="form.errors.graduate_first_name" />

                    <!-- Graduate Middle Initial -->
                    <InputLabel for="graduate_middle_initial" value="Graduate Middle Initial" />
                    <TextInput
                        id="graduate_middle_initial"
                        v-model="form.graduate_middle_initial"
                        type="text"
                        class="mt-1 block w-full"
                        required/>  
                    <InputError class="mt-2" :message="form.errors.graduate_middle_initial" />

                    <!-- Graduate Last Name -->
                    <InputLabel for="graduate_last_name" value="Graduate Last Name" />
                    <TextInput
                        id="graduate_last_name"
                        v-model="form.graduate_last_name"
                        type="text"
                        class="mt-1 block w-full"
                        required/>
                    <InputError class="mt-2" :message="form.errors.graduate_last_name" />
                        
                    <!-- Graduate Graduated From -->
                    <div class="mt-4">
                        <InputLabel for="graduate_school_graduated_from" value="School Graduated From" />
                        <select
                            id="graduate_school_graduated_from"
                            v-model="form.graduate_school_graduated_from"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            required
                            >
                            <option value="" disabled>Select a School</option>
                            <option v-for="user in insti_users"  :key="user.id" :value="user.institution_name">
                                {{ user.institution_name }}
                            </option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.graduate_school_graduated_from" />
                    </div>

                    <!-- Program Completed -->

                    <!-- Year Graduated -->
                    <div class="mt-4">
                    <InputLabel for="graduate_year_graduated" value="Year Graduated" />
                    <select
                        id="graduate_year_graduated"
                        v-model="form.graduate_year_graduated"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        required>
                        <option value="" disabled>Select Year</option>
                        <option v-for="year in school_year" :key="year" :value="year.school_year_range">{{ year.school_year_range }}</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.graduate_year_graduated" />

                    </div>

                        <!-- Graduate Program Completed -->
                        <div class="mt-4">
                            <InputLabel for="graduate_program_completed" value="Program Completed" />
                            <TextInput
                                id="graduate_program_completed"
                                v-model="form.graduate_program_completed"
                                type="text"
                                class="mt-1 block w-full"
                                required/>
                            <InputError class="mt-2" :message="form.errors.graduate_program_completed" />
                        </div>

                    </div>

                    <div class="grid grid-cols-3 ">
                        <div class=" col-span-1">
                            <h2 class="text-lg font-semibold text-gray-900"></h2>
                            <p class="text-sm text-gray-600"></p>
                        </div>

                        <div class=" col-span-2">
                            <div class="grid grid-cols-2 gap-4">
                            <!-- Gender -->
                            <div>
                                <div class="flex items-center gap-1">
                                    <InputLabel for="gender" value="Gender" />
                                    <span class="text-red-500">*</span>
                                </div>
                                <div>
                                    <select id="gender" v-model="form.gender" class="mt-1 mb-4 block w-full" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <InputError class="" :message="form.errors.gender" />
                                </div>
                            </div>
                            <!-- Date of Birth -->
                            <div>
                                <div class="flex items-center gap-1">
                                    <InputLabel for="dob" value="Date of Birth" />
                                    <span class="text-red-500">*</span>
                                </div>
                                <div>
                                    <TextInput 
                                        id="dob" 
                                        v-model="form.dob" 
                                        type="date" 
                                        class="mt-1 mb-4 block w-full" 
                                        required />
                                    <InputError class="mt-2" :message="form.errors.dob" />
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
                                <InputLabel for="contact_number" value="Contact Number" />
                                <span class="text-red-500">*</span>
                            </div>
                            <div>
                                <TextInput 
                                    id="contact_number"
                                    v-model="form.contact_number"
                                    v-mask="'# (###) ###-####'"
                                    type="text"
                                    class="mt-1 mb-4 block w-full"
                                    required />
                                <InputError class="mt-2" :message="form.errors.contact_number" />
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
                                    required 
                                    @focus="isPasswordFocused = true"
                                    @blur="isPasswordFocused = false" />
                                <InputError class="mt-1" :message="form.errors.password" />
                            </div>

                            <!-- Password validation tooltip UPDATE 04/04-->
                            <div v-if="isPasswordFocused && form.password" class="mt-2 p-3 bg-gray-800 text-white rounded-md w-64 text-sm shadow-lg">
                                <p class="font-semibold text-gray-200">Password must meet the following:</p>
                                <ul class="mt-1">
                                    <li :class="passwordCriteria.length ? 'text-green-400' : 'text-red-400'">
                                        ✔ Minimum 8 characters
                                    </li>
                                    <li :class="passwordCriteria.uppercaseLowercase ? 'text-green-400' : 'text-red-400'">
                                        ✔ Upper & lower case letters
                                    </li>
                                    <li :class="passwordCriteria.number ? 'text-green-400' : 'text-red-400'">
                                        ✔ At least one number
                                    </li>
                                    <li :class="passwordCriteria.symbol ? 'text-green-400' : 'text-red-400'">
                                        ✔ At least one special character (@$!%*?&)
                                    </li>
                                </ul>
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
                

                <div class="flex items-center justify-end mt-8 border-t border-gray-200 pt-12">
              
                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Register
                    </PrimaryButton>
                </div>
            
            </form>
        

        </template>

    </AuthenticationCard>


    <Modal v-if="showModal" :show="showModal" @close="redirectToLogin">
        <template #default>
            <div class="text-center">
                <h2 class="text-lg font-semibold">Registration Successful</h2>
                <p class="mt-2 text-gray-600">You have registered successfully. Click "Okay" to proceed to the login page.</p>
                <button
                    class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition"
                    @click="redirectToLogin"
                    >
                    Okay
                </button>
            </div>
        </template>
    </Modal>

</template>