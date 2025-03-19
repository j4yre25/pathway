<template>
    <AppLayout title="Institution Management">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Institution Management</h2>
        </template>
  
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-xl sm:rounded-lg p-6">
  
                    <!-- Navigation Tabs -->
                    <div class="border-b mb-4">
                        <nav class="flex space-x-4">
                          <button 
                            v-for="tab in tabs" 
                            :key="tab" 
                            @click="selectTab(tab)"
                            :class="{ 'text-blue-600 border-b-2 border-blue-600': selectedTab === tab }"
                          >
                            {{ tab }}
                          </button>
  
                        </nav>
                    </div>
  
                    <!-- School Year Tab -->
                    <div v-if="selectedTab === 'School Year'">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold">School Year List</h2>
                            <PrimaryButton @click="openSchoolYearModal">Add School Year</PrimaryButton>
                        </div>
  
                        <table class="min-w-full border rounded-lg">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="p-2 text-left">School Year Range</th>
                                    <th class="p-2 text-left">Term</th>
                                    <th class="p-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="year in props.schoolYears" :key="year.id" class="border-b">
                                    <td class="p-2">{{ year.school_year_range }}</td>
                                    <td class="p-2">{{ year.term }}</td>
                                    <td class="p-2 text-right">
                                        <Menu as="div" class="relative">
                                            <MenuButton class="text-gray-500 hover:text-gray-700">
                                                &#x22EE;
                                            </MenuButton>
                                            <MenuItems class="absolute right-0 w-40 bg-white shadow-md rounded-md">
                                                <MenuItem v-slot="{ active }">
                                                    <button @click="editingYear = year; isSchoolYearModalOpen = true" class="w-full text-left px-4 py-2" :class="{ 'bg-gray-100': active }">
                                                        Edit
                                                    </button>
                                                </MenuItem>
                                                <MenuItem v-slot="{ active }">
                                                    <button @click="deleteYear(year.id)" class="w-full text-left px-4 py-2 text-red-500" :class="{ 'bg-gray-100': active }">
                                                        Delete
                                                    </button>
                                                </MenuItem>
                                            </MenuItems>
                                        </Menu>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
  
                        <!-- School Year Modal -->
                        <SchoolYearModal 
                            :show="isSchoolYearModalOpen" 
                            :year="editingYear" 
                            @close="isSchoolYearModalOpen = false"
                            @save="saveYear"
                        />
                    </div>
  
                    <!-- Programs Tab -->
                    <div v-if="selectedTab === 'Programs'">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold">Programs List</h2>
                            <PrimaryButton @click="openProgramModal">Add Program</PrimaryButton>
                        </div>
  
                        <table class="min-w-full border rounded-lg">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="p-2 text-left">Program Name</th>
                                    <th class="p-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="program in props.programs" :key="program.id" class="border-b">
                                    <td class="p-2">{{ program.name }}</td>
                                    <td class="p-2 text-right">
                                        <Menu as="div" class="relative">
                                            <MenuButton class="text-gray-500 hover:text-gray-700">
                                                &#x22EE;
                                            </MenuButton>
                                            <MenuItems class="absolute right-0 w-40 bg-white shadow-md rounded-md">
                                                <MenuItem v-slot="{ active }">
                                                    <button @click="editingProgram = program; isProgramModalOpen = true" class="w-full text-left px-4 py-2" :class="{ 'bg-gray-100': active }">
                                                        Edit
                                                    </button>
                                                </MenuItem>
                                                <MenuItem v-slot="{ active }">
                                                    <button @click="deleteProgram(program.id)" class="w-full text-left px-4 py-2 text-red-500" :class="{ 'bg-gray-100': active }">
                                                        Delete
                                                    </button>
                                                </MenuItem>
                                            </MenuItems>
                                        </Menu>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
  
                        <!-- Program Modal -->
                        <ProgramModal 
                            :show="isProgramModalOpen" 
                            :program="editingProgram" 
                            @close="isProgramModalOpen = false"
                            @save="saveProgram"
                        />
                    </div>
  
                    <!-- Career Opportunities Tab -->
                    <div v-if="selectedTab === 'Career Opportunities'">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold">Career Opportunities List</h2>
                            <PrimaryButton @click="openCareerModal">Add Career Opportunity</PrimaryButton>
                        </div>
  
                        <table class="min-w-full border rounded-lg">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="p-2 text-left">Program</th>
                                    <th class="p-2 text-left">Opportunities</th>
                                    <th class="p-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                              <tr v-for="opportunity in careerOpportunities" :key="opportunity.id" class="border-b">
                                          <td class="p-2">{{ opportunity.program?.name }}</td> <!-- Ensure program exists -->
                                          <td class="p-2">{{ opportunity.title }}</td>
                                    <td class="p-2 text-right">
                                        <Menu as="div" class="relative">
                                            <MenuButton class="text-gray-500 hover:text-gray-700">
                                                &#x22EE;
                                            </MenuButton>
                                            <MenuItems class="absolute right-0 w-40 bg-white shadow-md rounded-md">
                                                <MenuItem v-slot="{ active }">
                                                    <button @click="editingOpportunity = opportunity; isCareerModalOpen = true" class="w-full text-left px-4 py-2" :class="{ 'bg-gray-100': active }">
                                                        Edit
                                                    </button>
                                                </MenuItem>
                                                <MenuItem v-slot="{ active }">
                                                    <button @click="deleteOpportunity(opportunity.id)" class="w-full text-left px-4 py-2 text-red-500" :class="{ 'bg-gray-100': active }">
                                                        Delete
                                                    </button>
                                                </MenuItem>
                                            </MenuItems>
                                        </Menu>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
  
                        <!-- Career Opportunity Modal -->
                        <CareerOpportunityModal 
                            :show="isCareerModalOpen" 
                            :opportunity="editingOpportunity" 
                            :programs="props.programs"
                            @close="isCareerModalOpen = false"
                            @save="saveOpportunity"
                        />
                    </div>
  
                </div>
            </div>
        </div>
    </AppLayout>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { router } from '@inertiajs/vue3';
  import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import SchoolYearModal from '@/Pages/Institutions/SchoolYearModal.vue';
  import ProgramModal from '@/Pages/Institutions/ProgramModal.vue';
  import CareerOpportunityModal from '@/Pages/Institutions/CareerOpportunityModal.vue';
  import AppLayout from '@/Layouts/AppLayout.vue';
  
  const props = defineProps({
    institutions: Array,
    schoolYears: Array,
    programs: Array,
    careerOpportunities: Array,
  });
  
  // Define tabs
  const tabs = ref(['School Year', 'Programs', 'Career Opportunities']);
  const isSchoolYearModalOpen = ref(false);
  const isProgramModalOpen = ref(false);
  const isCareerModalOpen = ref(false);
  const editingYear = ref(null);
  const editingProgram = ref(null);
  const editingOpportunity = ref(null);
  const selectedTab = ref(tabs.value[0]); // Default tab
  
  const selectTab = (tab) => {
      selectedTab.value = tab;
      router.reload(); // Reload data to reflect the latest changes
  };
  
  
  
  const openSchoolYearModal = () => {
    editingYear.value = null; // Reset for adding a new entry
    isSchoolYearModalOpen.value = true;
  };
  
  const openProgramModal = () => {
    editingProgram.value = null; // Reset for adding a new entry
    isProgramModalOpen.value = true;
  };
  
  const openCareerModal = () => {
    editingOpportunity.value = null; // Reset for adding a new entry
    isCareerModalOpen.value = true;
  };
  
  const saveYear = (yearData) => {
    if (editingYear.value) {
        router.patch(`/school-years/${editingYear.value.id}`, yearData, {
            onSuccess: () => {
                isSchoolYearModalOpen.value = false;
                router.visit('/institution-management', { preserveState: true });
            },
        });
    } else {
        router.post('/school-years', yearData, {
            onSuccess: () => {
                isSchoolYearModalOpen.value = false;
            },
        });
    }
  };
  
  const saveProgram = (programData) => {
    if (editingProgram.value) {
        router.patch(`/programs/${programData.id}`, programData, {
            onSuccess: () => {
                isProgramModalOpen.value = false;
                router.visit('/institution-management', { preserveState: true });
            },
        });
    } else {
        router.post('/programs', programData, {
            onSuccess: () => {
                isProgramModalOpen.value = false;
            },
        });
    }
  };
  
  const saveOpportunity = (opportunityData) => { 
    if (editingOpportunity.value) { 
        router.patch(`/career-opportunities/${editingOpportunity.value.id}`, opportunityData, { 
            onSuccess: () => { 
                isCareerModalOpen.value = false; 
                router.visit('/institution-management', { preserveState: true });
            }, 
        }); 
    } else { 
        router.post('/career-opportunities', { 
            title: opportunityData.title, 
            program_id: opportunityData.program_id, 
        }, { 
            onSuccess: () => { 
                isCareerModalOpen.value = false; 
                router.reload(); // Reload to update the list
            }, 
        }); 
    } 
  };
  
  
  const deleteYear = (id) => {
    if (confirm('Are you sure you want to delete this school year?')) {
        router.delete(`/school-years/${id}`);
    }
  };
  
  const deleteProgram = (id) => {
    if (confirm('Are you sure you want to delete this program?')) {
        router.delete(`/programs/${id}`);
    }
  };
  
  const deleteOpportunity = (id) => {
    if (confirm('Are you sure you want to delete this career opportunity?')) {
        router.delete(`/career-opportunities/${id}`);
    }
  };
  </script>