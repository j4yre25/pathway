<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link } from '@inertiajs/vue3';
import Container from '@/Components/Container.vue';



const props = defineProps({
    sectors: Array
});

// console.log(props.sectors);


</script>

<template>

    <div class="overflow-x-auto">
      <table class="min-w-full bg-white border border-gray-200">
        <thead>
          <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
            <th class="border border-gray-200 px-6 py-3 text-left">ID</th>
            <th class="border border-gray-200 px-6 py-3 text-left">Name</th>
            <th class="border border-gray-200 px-6 py-3 text-left">Actions</th>
          </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
          <tr v-for="sector in sectors" :key="sector.id" class="border-b border-gray-200 hover:bg-gray-100">
            <td class="border border-gray-200 px-6 py-4">{{ sector.id }}</td>
            <td class="border border-gray-200 px-6 py-4">{{ sector.name }}</td>
            <td class="border border-gray-200 px-6 py-4">
            <Link :href="route('sectors.edit', { sector: sector.id })">
              <PrimaryButton class="mr-2">Edit</PrimaryButton>
            </Link>
        
          </td>
          </tr>
        </tbody>
      </table>
    </div>

 

</template>