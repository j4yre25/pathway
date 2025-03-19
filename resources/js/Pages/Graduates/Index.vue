<template>
    <AppLayout title="Graduate Management">
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Graduate Management</h2>
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
                  @click="selectedTab = tab"
                  class="px-4 py-2 text-sm font-medium rounded-t-md"
                  :class="selectedTab === tab ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700'">
                  {{ tab }}
                </button>
              </nav>
            </div>
  
            <!-- Graduates Tab -->
            <div v-if="selectedTab === 'Graduates'">
                <h2 class="text-xl font-semibold">Graduate List</h2>
              <div class="flex justify-end items-center mb-4">
                
                <PrimaryButton @click="openAddModal">Add Graduate</PrimaryButton>
                <PrimaryButton @click="openBatchUploadModal" class="ml-2">Batch Upload</PrimaryButton>
              </div>
  
              <table class="min-w-full border rounded-lg">
                <thead class="bg-gray-200">
                  <tr>
                    <th class="p-2 text-left">Name</th>
                    <th class="p-2 text-left">Program</th>
                    <th class="p-2 text-left">Year Graduated</th>
                    <th class="p-2 text-left">Current Job Title</th>
                    <th class="p-2"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="graduate in props.graduates" :key="graduate.id" class="border-b">
                    <td class="p-2">{{ graduate.name }}</td>
                    <td class="p-2">{{ graduate.program_name }}</td>
                    <td class="p-2">{{ graduate.year_graduated }}</td>
                    <td class="p-2">{{ graduate.current_job_title ?? 'N/A' }}</td>
                    <td class="p-2 text-right">
                      <Menu as="div" class="relative">
                        <MenuButton class="text-gray-500 hover:text-gray-700">
                          &#x22EE;
                        </MenuButton>
                        <MenuItems class="absolute right-0 w-40 bg-white shadow-md rounded-md">
                          <MenuItem v-slot="{ active }">
                            <button @click="openEditModal(graduate)" class="w-full text-left px-4 py-2" :class="{ 'bg-gray-100': active }">
                              Edit
                            </button>
                          </MenuItem>
                          <MenuItem v-slot="{ active }">
                            <button @click="deleteGraduate(graduate.id)" class="w-full text-left px-4 py-2 text-red-500" :class="{ 'bg-gray-100': active }">
                              Delete
                            </button>
                          </MenuItem>
                        </MenuItems>
                      </Menu>
                    </td>
                  </tr>
                </tbody>
              </table>
  
              <!-- Graduate Modal -->
              <GraduateModal 
                :show="isModalOpen" 
                :graduate="editingGraduate" 
                :programs="props.programs" 
                :years="props.years"
                @close="isModalOpen = false"
                @save="saveGraduate"
              />
  
              <!-- Batch Upload Modal -->
              <BatchUploadModal 
                :show="isBatchUploadModalOpen" 
                @close="isBatchUploadModalOpen = false" 
              />
  
            </div>
  
          </div>
        </div>
      </div>
    </AppLayout>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue';
  import { useForm, router } from '@inertiajs/vue3';
  import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import GraduateModal from '@/Pages/Graduates/GraduateModal.vue';
  import BatchUploadModal from '@/Pages/Graduates/BatchUploadModal.vue'; // Import the Batch Upload Modal
  import AppLayout from '@/Layouts/AppLayout.vue';
  
  const props = defineProps({
      graduates: Array,
      programs: Array,
      years: Array,
  });
  
  const isModalOpen = ref(false);
  const isBatchUploadModalOpen = ref(false);
  const editingGraduate = ref(null);
  const selectedTab = ref('Graduates'); // Default active tab
  const tabs = ['Graduates']; // Only keep the Graduates tab
  
  const openAddModal = () => {
      editingGraduate.value = null;
      isModalOpen.value = true;
  };
  
  const openBatchUploadModal = () => {
      isBatchUploadModalOpen.value = true;
  };
  
  const openEditModal = (graduate) => {
      editingGraduate.value = { ...graduate };
      isModalOpen.value = true;
  };
  
  const saveGraduate = (graduateData) => {
      if (editingGraduate.value) {
          router.patch(`/graduates/${graduateData.id}`, graduateData, {
              onSuccess: () => isModalOpen.value = false,
          });
      } else {
          router.post('/graduates', graduateData, {
              onSuccess: () => isModalOpen.value = false,
          });
      }
  };
  
  const deleteGraduate = (id) => {
      if (confirm('Are you sure you want to delete this graduate?')) {
          router.delete(`/graduates/${id}`);
      }
  };
  </script>
  