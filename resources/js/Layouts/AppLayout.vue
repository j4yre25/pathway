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

const props = defineProps({
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

        form.company_contact_number= rawValue;
    },
});

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

const redirectToLogin = () => {
    Inertia.visit(route('login'));
};

</script>

<template>

    <Head title="Register" />

    <AuthenticationCard>
        


        <!-- UPDATE 04/04 Putting form inside the template for registrtion form layout -->
        <template #registerForm>

            

<<<<<<< HEAD
            <form @submit.prevent="submit" autocomplete="off">
                <!-- Company Fields -->
             <div v-if="form.role === 'company'" class="flex space-x-12  ">
                    <div class="flex-1 flex flex-col items-start justify-center p-6 bg-gradient-to-r from-purple-600 to-blue-500 rounded-lg shadow-lg text-white ">
                        <AuthenticationCardLogo class="mx-20 fill-white-100" />
                        <h2 class="text-6xl font-bold">Welcome to</h2>
                        <h1 class="text-7xl font-extrabold">Pathway</h1>
                        <p class="mt-4 text-sm">
                            Join us in shaping the future of education. We are excited to partner with you in this
                            journey.
                        </p>
=======
                                <NavLink v-if="page.props.roles.isPeso"
                                    :href="route('sectors', { user: page.props.auth.user.id })"
                                    :active="route().current('sectors')">
                                    Manage Sectors
                                </NavLink>

                                <NavLink :href="route('categories.index', { user: page.props.auth.user.id })"
                                    v-if="page.props.roles.isPeso" Categories
                                    :active="route().current('categories.index')">
                                    Manage Categories
                                </NavLink>


                                <NavLink
                                    
                                    v-if="page.props.roles.isCompany || page.props.roles.isInstitution && page.props.auth.user.is_approved"
                                    :href="route('jobs', { user: page.props.auth.user.id })"
                                    :active="route().current('jobs')" :disabled="!page.props.auth.user.is_approved">
                                    Manage Job Posting
                                </NavLink>

                                
                                <NavLink
                                    
                                    v-if="page.props.roles.isPeso && page.props.auth.user.is_approved"
                                    :href="route('peso.jobs', { user: page.props.auth.user.id })"
                                    :active="route().current('pesojobs')" :disabled="!page.props.auth.user.is_approved">
                                    PESO Job Posting
                                </NavLink>


                                <NavLink v-if="page.props.roles.isCompany || page.props.roles.isInstitution " :href="route('jobs' , { user: page.props.auth.user.id })" :active="route().current('jobs')"    :disabled="!page.props.auth.user.is_approved"
                                >
                                    Manage Applicants
                                </NavLink>

                                <NavLink v-if="page.props.roles.isCompany" :href="route('dashboard' , { user: page.props.auth.user.id })" :active="route().current('dashboard')"    :disabled="!page.props.auth.user.is_approved"
                                >
                                    Manage HR Accounts
                                </NavLink>

                                <NavLink v-if="page.props.roles.isCompany" :href="route('dashboard' , { user: page.props.auth.user.id })" :active="route().current('jdashboard')"    :disabled="!page.props.auth.user.is_approved"
                                >
                                    Reports
                                </NavLink>

                                <NavLink :href="route('dashboard')" v-if="page.props.roles.isPeso" Categories
                                    :active="route().current('dashboard')">
                                    Manage Job Referrals
                                </NavLink>
<!-- 
                                <NavLink :href="route('jobs.list')" v-if="page.props.roles.isPeso" Categories
                                    :active="route().current('job.list')">
                                    Reports
                                </NavLink> -->


                                <!-- Graduate Link -->
                                <NavLink
                                    v-if="page.props.permissions.canManageGraduate && page.props.auth.user.is_approved"
                                    :href="route('graduates.index')" :active="route().current('graduates.index')">
                                    Graduate
                                </NavLink>

                                


                                <!-- Institution Link -->
                                <NavLink
                                    v-if="page.props.permissions.canManageInstitution && page.props.auth.user.is_approved"
                                    :href="route('institutions.index')" :active="route().current('institutions.index')">
                                    Institution
                                </NavLink>

                                <NavLink
                                    v-if="page.props.permissions.canManageInstitution && page.props.auth.user.is_approved"
                                    :href="route('institutions.index')" :active="route().current('institutions.index')">
                                    Manage Career Officer
                                </NavLink>

                                <NavLink
                                    v-if="page.props.permissions.canManageInstitution && page.props.auth.user.is_approved"
                                    :href="route('institutions.index')" :active="route().current('institutions.index')">
                                    Manage Graduate Counseling
                                </NavLink>

                                <!-- Manage Approval Link -->
                                <NavLink
                                    v-if="page.props.permissions.canManageApprovalGraduate && page.props.auth.user.is_approved"
                                    :href="route('institution.manage_users')"
                                    :active="route().current('institution.manage_users')">
                                    Manage Approval
                                </NavLink>

                            

                                <!--<NavLink 
                                    v-if="sectors" 

                                 <NavLink :href="route('sectors' , { user: page.props.auth.user.id })" :active="route().current('sectors')" >
                                    Sectors 
                                </NavLink> -->


                                <!-- <NavLink 
                                    v-if="sector" 

                                    :href="route('categories', { sector: sector.id })" 
                                    :active="route().current('categories')"
                                >
                                    Categories
                                </NavLink>
                                
                            

                                <NavLink :href="route('manage_graduates')" :active="route().current('manage_graduates')">
                                    Manage Graduates
                                </NavLink> -->

                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <div class="ms-3 relative">
                                <!-- Teams Dropdown -->
                                <Dropdown v-if="$page.props.jetstream.hasTeamFeatures" align="right" width="60">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                                {{ $page.props.auth.user.current_team.name }}

                                                <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <div class="w-60">
                                            <!-- Team Management -->
                                            <div class="block px-4 py-2 text-xs text-gray-400">
                                                Manage Team
                                            </div>

                                            <!-- Team Settings -->
                                            <DropdownLink
                                                :href="route('teams.show', $page.props.auth.user.current_team)">
                                                Team Settings
                                            </DropdownLink>

                                            <DropdownLink v-if="$page.props.jetstream.canCreateTeams"
                                                :href="route('teams.create')">
                                                Create New Team
                                            </DropdownLink>

                                            <!-- Team Switcher -->
                                            <template v-if="$page.props.auth.user.all_teams.length > 1">
                                                <div class="border-t border-gray-200" />

                                                <div class="block px-4 py-2 text-xs text-gray-400">
                                                    Switch Teams
                                                </div>

                                                <template v-for="team in $page.props.auth.user.all_teams"
                                                    :key="team.id">
                                                    <form @submit.prevent="switchToTeam(team)">
                                                        <DropdownLink as="button">
                                                            <div class="flex items-center">
                                                                <svg v-if="team.id == $page.props.auth.user.current_team_id"
                                                                    class="me-2 size-5 text-green-400"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24" stroke-width="1.5"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>

                                                                <div>{{ team.name }}</div>
                                                            </div>
                                                        </DropdownLink>
                                                    </form>
                                                </template>
                                            </template>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>

                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button v-if="$page.props.jetstream.managesProfilePhotos"
                                            class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                            <img class="size-8 rounded-full object-cover"
                                                :src="$page.props.auth.user.profile_photo_url"
                                                :alt="$page.props.auth.user.name">
                                        </button>

                                        <span v-else class="inline-flex rounded-md">
                                            <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                                <template v-if="$page.props.auth.user.role === 'graduate'">
                                                    {{ $page.props.auth.user.graduate_first_name }}
                                                </template>
                                                <template v-if="$page.props.auth.user.role === 'peso'">
                                                    {{ $page.props.auth.user.peso_first_name }}
                                                </template>
                                                <template v-else-if="$page.props.auth.user.role === 'company'">
                                                    {{ $page.props.auth.user.company_name }}
                                                </template>
                                                <template v-else-if="$page.props.auth.user.role === 'institution'">
                                                    {{ $page.props.auth.user.institution_name }}
                                                </template>
                                                <template v-else>
                                                    {{ $page.props.auth.user.name }}
                                                </template>

                                                <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            Manage Account
                                        </div>


                                        <DropdownLink
                                            v-if="page.props.roles.isPeso && page.props.auth.user.is_approved"
                                            :disabled="!page.props.auth.user.is_approved" :href="route('peso.profile')">
                                            Profile
                                        </DropdownLink>


                                        <DropdownLink v-if="page.props.roles.isGraduate"
                                            :href="route('profile.index', { user: page.props.auth.user.id })"
                                            :active="route().current('profile.index')">
                                            Profile Settings </DropdownLink>

                                        <DropdownLink v-if="page.props.roles.isPeso" :href="route('admin.register')">
                                            Admin Registration
                                        </DropdownLink>


                                        <DropdownLink
                                            v-if="page.props.roles.isCompany && page.props.auth.user.is_approved"
                                            :disabled="!page.props.auth.user.is_approved" :href="route('company.profile')">
                                            Profile
                                        </DropdownLink>
                                        
                                        <DropdownLink
                                            v-if="page.props.roles.isCompany && page.props.auth.user.is_approved"
                                            :disabled="!page.props.auth.user.is_approved" :href="route('hr.register')">
                                            Add Human Resource Officer (HRO)

                                        </DropdownLink>

                                        <DropdownLink v-if="$page.props.jetstream.hasApiFeatures"
                                            :href="route('api-tokens.index')">
                                            API Tokens
                                        </DropdownLink>

                                        <div class="border-t border-gray-200" />

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <DropdownLink as="button">
                                                Log Out
                                            </DropdownLink>
                                        </form>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                                @click="showingNavigationDropdown = !showingNavigationDropdown">
                                <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{ 'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                    <path
                                        :class="{ 'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{ 'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown }"
                    class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <!-- <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink> -->

                        

                        <ResponsiveNavLink :href="route('jobs', { user: page.props.auth.user.id })"
                            :active="route().current('jobs')">
                            Jobs
                        </ResponsiveNavLink>
                        <ResponsiveNavLink v-if="page.props.roles.isPeso"
                            :href="route('admin.manage_users', { user: page.props.auth.user.id })"
                            :active="route().current('admin.manage_users')">
                            Manage Users
                        </ResponsiveNavLink>

                        <ResponsiveNavLink v-if="page.props.roles.isPeso"
                            :href="route('', { user: page.props.auth.user.id })" :active="route().current('')">
                            List of Users
                        </ResponsiveNavLink>

>>>>>>> 1236799fa2a05b7c821cc17e108f9fca41f9f193
                    </div>

                    <div class="flex-1 space-y-2">
                        <h2 class="text-xl font-semibold text-gray-900">Company Information</h2>
                        <p class="text-sm text-gray-600">Provide key details about your company.</p>

                        <div class="grid grid-cols-1 gap-4">
                            <!-- Company Name -->
                            <div>
                                <div class="flex items-center gap-1 col-span-6 sm:col-span-4">
                                    <InputLabel for="company_name" value="Company Name" />
                                    <span class="text-red-500">*</span>
                                </div>
                                <div>
                                    <TextInput id="company_name" v-model="form.company_name" type="text"
                                        class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:shadow-lg"
                                        required />
                                    <InputError class="mt-2" :message="form.errors.company_name" />
                                </div>
                            </div>

                            <!-- Company Address -->
                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-2">
                                    <div class="flex items-center gap-1">
                                        <InputLabel for="company_street_address" value="Street Address" />
                                        <span class="text-red-500">*</span>
                                    </div>
                                    <div>
                                        <TextInput id="company_street_address" v-model="form.company_street_address"
                                            type="text" class="mt-1 mb-4 block w-full l border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:shadow-lg" 
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
                                        <TextInput id="company_brgy" v-model="form.company_brgy" type="text"
                                            class="mt-1 mb-4 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:shadow-lg" 
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
                                        <TextInput id="company_city" v-model="form.company_city" type="text"
                                            class="mt-1 mb-4 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:shadow-lg"  
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
                                        <TextInput id="company_province" v-model="form.company_province" type="text"
                                            class="mt-1 mb-4 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:shadow-lg" 
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
                                        <TextInput id="company_zip_code" v-model="form.company_zip_code" type="text"
                                            class="mt-1 mb-4 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:shadow-lg" 
                                            required />
                                        <InputError class="mt-1" :message="form.errors.company_zip_code" />
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-gray-200"></div> 
                            <h2 class="text-xl font-semibold text-gray-900">Company Contact Information</h2>
                            <p class="text-sm text-gray-600 ">
                                Provide the official contact details of your company.
                            </p>
                            <!-- Company Email -->
                            <div>
                                <div class="flex items-center gap-1">
                                    <InputLabel for="company_email" value="Email Address" />
                                    <span class="text-red-500">*</span>
                                </div>
                                <div>
                                    <TextInput id="company_email" v-model="form.company_email" type="email"
                                        class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:shadow-lg"
                                        required />
                                    <InputError class="mt-2" :message="form.errors.company_email" />
                                </div>
                            </div>

                            <!-- Mobile and Telephone Number -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div>
                                        <div class="flex items-center gap-1">
                                            <InputLabel for="company_contact_number" value="Mobile Number" />
                                            <span class="text-red-500">*</span>
                                        </div>
                                        <TextInput id="company_contact_number" v-model="formattedContactNumber" type="text"
                                            class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:shadow-lg"
                                            required />
                                        <InputError class="mt-2" :message="form.errors.company_contact_number" />
                                    </div>
                                </div>
                                <div>
                                    <InputLabel for="telephone_number" value="Telephone Number" />
                                    <TextInput id="telephone_number" v-model="form.telephone_number" type="text"
                                        class="mt-1 mb-4 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:shadow-lg"
                                        />
                                    <InputError class="mt-2" :message="form.errors.telephone_number" />
                                </div>
                            </div>
                             
                            <div class="border-t border-gray-200"></div> 
                            <h2 class="text-xl font-semibold text-gray-900">Human Resource Officer Personal Information</h2>
                            <p class="text-sm text-gray-600 ">
                                Provide the personal details of your HR.
                            </p>
                            
                            <!-- HR's First and Last Name -->
                            <div class="grid grid-cols-2 gap-x-4">
                               <!-- First Name -->
                                <div> 
                                    <div class="flex items-center gap-1">
                                        <InputLabel for="company_hr_first_name" value="First Name" />
                                        <span class="text-red-500">*</span>
                                    </div>
                                    <div>
                                        <TextInput id="company_hr_first_name" v-model="form.company_hr_first_name"
                                            type="text" 
                                            class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:shadow-lg"
                                            required />
                                        <InputError class="mt-2" :message="form.errors.company_hr_first_name" />
                                    </div>
                                </div>
                                <div> <!-- Last Name -->
                                    <div class="flex items-center gap-1">
                                        <InputLabel for="company_hr_last_name" value="Last Name" />
                                        <span class="text-red-500">*</span>
                                    </div>
                                    <div>
                                        <TextInput id="company_hr_last_name" v-model="form.company_hr_last_name"
                                            type="text" 
                                            class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:shadow-lg"
                                            required />
                                        <InputError class="mt-2" :message="form.errors.company_hr_last_name" />
                                    </div>
                                </div>
                                </div>  
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Gender -->
                                    <div>
                                        <div class="flex items-center gap-x-4">
                                            <InputLabel for="gender" value="Gender" />
                                            <span class="text-red-500">*</span>
                                        </div>
                                        <div>
                                            <select id="gender" v-model="form.gender" 
                                            class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:shadow-lg" 
                                            required>
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
                                            <TextInput id="dob" v-model="form.dob" type="date" 
                                                class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:shadow-lg"
                                                required />
                                            <InputError class="mt-2" :message="form.errors.dob" />
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-x-4">
                                    <!-- HR Email -->
                                    <div>
                                        <div class="flex items-center gap-1">
                                            <InputLabel for="email" value="Email Address" />
                                            <span class="text-red-500">*</span>
                                        </div>
                                        <div>
                                            <TextInput id="email" v-model="form.email" type="email" 
                                                class="mt-1 mb-4 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:shadow-lg"
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
                                            <TextInput id="contact_number" v-model="form.contact_number" v-mask="'# (###) ###-####'"
                                                type="text"
                                                class="mt-1 mb-4 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:shadow-lg"
                                                required />
                                            <InputError class="mt-2" :message="form.errors.contact_number" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Set Password -->
                                <h2 class="text-lg font-semibold text-gray-900">Set Password</h2>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <div class="flex items-center gap-1">
                                            <InputLabel for="password" value="Password" />
                                            <span class="text-red-500">*</span>
                                        </div>
                                        <div class="mb-2">
                                            <TextInput id="password" v-model="form.password" type="password"
                                                class="mt-1 block w-full" required @focus="isPasswordFocused = true"
                                                @blur="isPasswordFocused = false" />
                                            <InputError class="mt-1" :message="form.errors.password" />
                                        </div>
                                        
                                        <div v-if="isPasswordFocused && form.password"
                                            class="mt-2 p-3 bg-gray-800 text-white rounded-md w-64 text-sm shadow-lg">
                                            <p class="font-semibold text-gray-200">Password must meet the following:</p>
                                            <ul class="mt-1">
                                                <li :class="passwordCriteria.length ? 'text-green-400' : 'text-red-400'">
                                                    ✔ Minimum 8 characters
                                                </li>
                                                <li
                                                    :class="passwordCriteria.uppercaseLowercase ? 'text-green-400' : 'text-red-400'">
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
                                    </div>
                                    
                                   <div>
                                        <div class="flex items-center gap-1">
                                            <InputLabel for="password_confirmation" value="Confirm Password" />
                                            <span class="text-red-500">*</span>
                                        </div>
                                        <div class="mb-2">
                                            <TextInput id="password_confirmation" v-model="form.password_confirmation"
                                                type="password" class="mt-1 mb-4 block w-full" required />
                                            <InputError class="mt-1" :message="form.errors.password_confirmation" />
                                        </div>
                                    </div>  
                                </div>
                             </div>
                        </div>
                    </div>

                <!-- Institution Fields UPDATED 04/04/2025
                     Separated the Password and Personal Information (Geneder, and etc.)
                     so it wont affect Institutuon. Naka Global abi -->
                <div v-if="form.role === 'institution'" class="flex space-x-12">
                    <!-- Left Side: Welcome Section -->
                    <div
                        class="flex-1 flex flex-col items-start justify-center p-6 bg-gradient-to-r from-purple-600 to-blue-500 rounded-lg shadow-lg text-white">
                        <h2 class="text-6xl font-bold">Welcome to</h2>
                        <h1 class="text-7xl font-extrabold">Pathway</h1>
                        <p class="mt-4 text-sm">
                            Join us in shaping the future of education. We are excited to partner with you in this
                            journey.
                        </p>
                    </div>

                    <!-- Right Side: Institution Fields -->
                    <div class="flex-1 space-y-2">
                        <h2 class="text-xl font-semibold text-gray-900">Institution Information</h2>
                        <p class="text-sm text-gray-600">Provide key details about your institution.</p>

                        <div class="grid grid-cols-1 gap-4">
                            <!-- Institution Type -->
                            <div>
                                <InputLabel for="institution_type" value="Institution Type" />
                                <select id="institution_type" v-model="form.institution_type"
                                    class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition duration-300 ease-in-out transform hover:shadow-lg"
                                    required>
                                    <option value="college">College</option>
                                    <option value="university">University</option>
                                    <option value="institution">Institution</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.institution_type" />
                            </div>

                            <!-- College/University/Institution Name -->
                            <div>
                                <InputLabel for="institution_name" value="College/University/Institution Name" />
                                <TextInput id="institution_name" v-model="form.institution_name" type="text"
                                    class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:shadow-lg"
                                    required />
                                <InputError class="mt-2" :message="form.errors.institution_name" />
                            </div>

                            <!-- Institution Address -->
                            <div>
                                <InputLabel for="institution_address" value="Institution Address" />
                                <TextInput id="institution_address" v-model="form.institution_address" type="text"
                                    class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:shadow-lg"
                                    required />
                                <InputError class="mt-2" :message="form.errors.institution_address" />
                            </div>

                            <!-- Institution Email -->
                            <div>
                                <InputLabel for="email" value="Institution Email" />
                                <TextInput id="email" v-model="form.email" type="email"
                                    class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:shadow-lg"
                                    required />
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>

                            <!-- Mobile and Telephone Number -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="contact_number" value="Mobile Number" />
                                    <TextInput id="contact_number" v-model="form.contact_number" type="text"
                                        class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:shadow-lg"
                                        required />
                                    <InputError class="mt-2" :message="form.errors.contact_number" />
                                </div>
                                <div>
                                    <InputLabel for="telephone_number" value="Telephone Number" />
                                    <TextInput id="telephone_number" v-model="form.telephone_number" type="text"
                                        class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:shadow-lg"
                                        required />
                                    <InputError class="mt-2" :message="form.errors.telephone_number" />
                                </div>
                            </div>

                            <!-- President's First and Last Name -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="institution_president_first_name" value="President First Name" />
                                    <TextInput id="institution_president_first_name"
                                        v-model="form.institution_president_first_name" type="text"
                                        class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:shadow-lg"
                                        required />
                                    <InputError class="mt-2" :message="form.errors.institution_president_first_name" />
                                </div>
                                <div>
                                    <InputLabel for="institution_president_last_name" value="President Last Name" />
                                    <TextInput id="institution_president_last_name"
                                        v-model="form.institution_president_last_name" type="text"
                                        class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:shadow-lg"
                                        required />
                                    <InputError class="mt-2" :message="form.errors.institution_president_last_name" />
                                </div>
                            </div>

                            <!-- Career Officer's First and Last Name -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="institution_career_officer_first_name"
                                        value="Career Officer First Name" />
                                    <TextInput id="institution_career_officer_first_name"
                                        v-model="form.institution_career_officer_first_name" type="text"
                                        class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:shadow-lg"
                                        required />
                                    <InputError class="mt-2"
                                        :message="form.errors.institution_career_officer_first_name" />
                                </div>
                                <div>
                                    <InputLabel for="institution_career_officer_last_name"
                                        value="Career Officer Last Name" />
                                    <TextInput id="institution_career_officer_last_name"
                                        v-model="form.institution_career_officer_last_name" type="text"
                                        class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:shadow-lg"
                                        required />
                                    <InputError class="mt-2"
                                        :message="form.errors.institution_career_officer_last_name" />
                                </div>
                            </div>

                            <!-- Set Password -->
                            <h2 class="text-lg font-semibold text-gray-900">Set Password</h2>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="flex items-center gap-1">
                                        <InputLabel for="password" value="Password" />
                                    </div>
                                    <TextInput id="password" v-model="form.password" type="password"
                                        class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:shadow-lg"
                                        required />
                                    <InputError class="mt-1" :message="form.errors.password" />
                                    <p class="mt-1 text-xs text-gray-500">
                                        Password must meet the following:
                                    <ul class="list-disc list-inside">
                                        <li>✔ Minimum 8 characters</li>
                                        <li>✔ Upper & lower case letters</li>
                                        <li>✔ At least one number</li>
                                        <li>✔ At least one special character (#@$!%*?&)</li>
                                    </ul>
                                    </p>
                                </div>
                                <div>
                                    <div class="flex items-center gap-1">
                                        <InputLabel for="password_confirmation" value="Confirm Password" />
                                    </div>
                                    <TextInput id="password_confirmation" v-model="form.password_confirmation"
                                        type="password"
                                        class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:shadow-lg"
                                        required />
                                    <InputError class="mt-1" :message="form.errors.password_confirmation" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graduate Fields -->
                <div v-if="form.role === 'graduate'" class="mt-4">
                    <!-- Graduate First Name -->
                    <InputLabel for="graduate_first_name" value="Graduate First Name" />
                    <TextInput id="graduate_first_name" v-model="form.graduate_first_name" type="text"
                        class="mt-1 block w-full" required />
                    <InputError class="mt-2" :message="form.errors.graduate_first_name" />

                    <!-- Graduate Middle Initial -->
                    <InputLabel for="graduate_middle_initial" value="Graduate Middle Initial" />
                    <TextInput id="graduate_middle_initial" v-model="form.graduate_middle_initial" type="text"
                        class="mt-1 block w-full" required />
                    <InputError class="mt-2" :message="form.errors.graduate_middle_initial" />

                    <!-- Graduate Last Name -->
                    <InputLabel for="graduate_last_name" value="Graduate Last Name" />
                    <TextInput id="graduate_last_name" v-model="form.graduate_last_name" type="text"
                        class="mt-1 block w-full" required />
                    <InputError class="mt-2" :message="form.errors.graduate_last_name" />

                    <!-- Graduate Graduated From -->
                    <div class="mt-4">
                        <InputLabel for="graduate_school_graduated_from" value="School Graduated From" />
                        <select id="graduate_school_graduated_from" v-model="form.graduate_school_graduated_from"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            required>
                            <option value="" disabled>Select a School</option>
                            <option v-for="user in insti_users" :key="user.id" :value="user.institution_name">
                                {{ user.institution_name }}
                            </option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.graduate_school_graduated_from" />
                    </div>

                    <!-- Program Completed -->
                    <div class="mt-4">
                        <InputLabel for="graduate_program_completed" value="Program Completed" />
                        <select
                            id="graduate_program_completed"
                            v-model="form.graduate_program_completed"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            required
                            >
                            <option value="" disabled>Select a Program</option>
                            <option v-for="program in availablePrograms":key="program.id" :value="program.name">
                                {{ program.name }}
                            </option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.graduate_program_completed" />
                    </div>

                    <!-- Year Graduated -->
                    <div class="mt-4">
                        <InputLabel for="graduate_year_graduated" value="Year Graduated" />
                        <select id="graduate_year_graduated" v-model="form.graduate_year_graduated"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            required>
                            <option value="" disabled>Select Year</option>
                            <option v-for="year in school_year" :key="year" :value="year.school_year_range">{{
                                year.school_year_range }}</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.graduate_year_graduated" />

                    </div>

                    <!-- Graduate Program Completed -->
                    <div class="mt-4">
                        <InputLabel for="graduate_program_completed" value="Program Completed" />
                        <TextInput id="graduate_program_completed" v-model="form.graduate_program_completed" type="text"
                            class="mt-1 block w-full" required />
                        <InputError class="mt-2" :message="form.errors.graduate_program_completed" />
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
                                    <TextInput id="dob" v-model="form.dob" type="date" class="mt-1 mb-4 block w-full"
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
                                <TextInput id="email" v-model="form.email" type="email" class="mt-1 mb-4 block w-full"
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
                                <TextInput id="contact_number" v-model="form.contact_number" v-mask="'# (###) ###-####'"
                                    type="text" class="mt-1 mb-4 block w-full" required />
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
                                <TextInput id="password" v-model="form.password" type="password"
                                    class="mt-1 block w-full" required @focus="isPasswordFocused = true"
                                    @blur="isPasswordFocused = false" />
                                <InputError class="mt-1" :message="form.errors.password" />
                            </div>

                            <!-- Password validation tooltip UPDATE 04/04-->
                            <div v-if="isPasswordFocused && form.password"
                                class="mt-2 p-3 bg-gray-800 text-white rounded-md w-64 text-sm shadow-lg">
                                <p class="font-semibold text-gray-200">Password must meet the following:</p>
                                <ul class="mt-1">
                                    <li :class="passwordCriteria.length ? 'text-green-400' : 'text-red-400'">
                                        ✔ Minimum 8 characters
                                    </li>
                                    <li
                                        :class="passwordCriteria.uppercaseLowercase ? 'text-green-400' : 'text-red-400'">
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
                                <TextInput id="password_confirmation" v-model="form.password_confirmation"
                                    type="password" class="mt-1 mb-4 block w-full" required />
                                <InputError class="mt-1" :message="form.errors.password_confirmation" />
                            </div>
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
                <p class="mt-2 text-gray-600">You have registered successfully. Click "Okay" to proceed to the login
                    page.</p>
                <button class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition"
                    @click="redirectToLogin">
                    Okay
                </button>
            </div>
        </template>
    </Modal>

</template>