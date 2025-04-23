<template>
    <div>
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-medium">School Years</h2>
        <PrimaryButton @click="showAdd = true">Add</PrimaryButton>
      </div>
  
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead>
            <tr class="bg-gray-100 text-gray-600 font-semibold">
              <th class="p-2">Range</th>
              <th class="p-2 text-right w-10"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in data" :key="item.id" class="hover:bg-gray-50">
              <td class="p-2">{{ item.range }}</td>
              <td class="p-2 text-right">
                <Dropdown align="right">
                  <template #trigger>
                    <button><EllipsisVertical class="w-5 h-5 text-gray-500" /></button>
                  </template>
                  <template #content>
                    <DropdownLink @click="editItem(item)">Edit</DropdownLink>
                    <DropdownLink @click="archiveItem(item.id)">Archive</DropdownLink>
                  </template>
                </Dropdown>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
  
      <EditModal v-if="edit" :model="edit" @close="edit = null" @saved="refresh" />
      <AddModal v-if="showAdd" @close="showAdd = false" @saved="refresh" />
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import PrimaryButton from '@/Components/PrimaryButton.vue'
  import Dropdown from '@/Components/Dropdown.vue'
  import DropdownLink from '@/Components/DropdownLink.vue'
  import { EllipsisVertical } from 'lucide-vue-next'
  
  import AddModal from '../Modals/AddSchoolYearModal.vue'
  import EditModal from '../Modals/EditSchoolYearModal.vue'
  import { router } from '@inertiajs/vue3'
  
  const props = defineProps({ data: Array })
  
  const showAdd = ref(false)
  const edit = ref(null)
  
  const editItem = (item) => edit.value = item
  
  const archiveItem = (id) => {
    router.delete(route('school-years.destroy', id), { preserveScroll: true })
  }
  
  const refresh = () => {
    emit('refresh')
  }
  </script>
  