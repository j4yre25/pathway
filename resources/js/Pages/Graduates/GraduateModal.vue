<template>
  <div v-if="show" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
      <h2 class="text-xl font-semibold mb-4">{{ graduate ? 'Edit Graduate' : 'Add Graduate' }}</h2>

      <form @submit.prevent="saveGraduate">
        <!-- Name -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700">Name</label>
          <input type="text" v-model="form.name" class="w-full p-2 border rounded" required>
          <p v-if="errors.name" class="text-red-500 text-sm">{{ errors.name }}</p>
        </div>

        <!-- Program -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700">Program</label>
          <select v-model="form.program_id" class="w-full p-2 border rounded" required>
            <option v-for="program in programs" :key="program.id" :value="program.id">{{ program.name }}</option>
          </select>
          <p v-if="errors.program_id" class="text-red-500 text-sm">{{ errors.program_id }}</p>
        </div>

        <!-- Year Graduated -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700">Year Graduated</label>
          <select v-model="form.year_graduated" class="w-full p-2 border rounded" required>
            <option v-for="year in years" :key="year" :value="parseInt(year)">{{ year }}</option>
          </select>
          <p v-if="errors.year_graduated" class="text-red-500 text-sm">{{ errors.year_graduated }}</p>
        </div>

        <!-- Employment Status -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700">Employment Status</label>
          <select v-model="form.employment_status" @change="handleEmploymentStatus" class="w-full p-2 border rounded" required>
            <option value="Employed">Employed</option>
            <option value="Underemployed">Underemployed</option>
            <option value="Unemployed">Unemployed</option>
          </select>
          <p v-if="errors.employment_status" class="text-red-500 text-sm">{{ errors.employment_status }}</p>
        </div>

        <!-- Current Job Title (Hidden if Unemployed) -->
        <div class="mb-4" v-if="form.employment_status !== 'Unemployed'">
          <label class="block text-sm font-medium text-gray-700">Current Job Title</label>
          <input type="text" v-model="form.current_job_title" class="w-full p-2 border rounded" placeholder="Ex. Software Engineer" />
          <p v-if="errors.current_job_title" class="text-red-500 text-sm">{{ errors.current_job_title }}</p>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end space-x-2 mt-4">
          <button @click="$emit('close')" type="button" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, defineProps, defineEmits } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  show: Boolean,
  graduate: Object,
  programs: Array,
  years: Array
});

const emit = defineEmits(['close', 'save']);

const form = ref({
  name: '',
  program_id: '',
  year_graduated: '',
  employment_status: 'Employed',
  current_job_title: ''
});

const errors = ref({});

// Watch for changes to graduate prop (for editing)
watch(() => props.graduate, (newGraduate) => {
  if (newGraduate) {
    form.value = { ...newGraduate };
  } else {
    form.value = { name: '', program_id: '', year_graduated: '', employment_status: 'Employed', current_job_title: '' };
  }
  errors.value = {}; // Reset errors when modal opens
}, { immediate: true });

const handleEmploymentStatus = () => {
  if (form.value.employment_status === 'Unemployed') {
    form.value.current_job_title = 'N/A';
  }
};

const saveGraduate = () => {
  errors.value = {}; // Clear previous errors
  
  if (form.value.id) {
    // If ID exists, update the existing graduate
    router.patch(route('graduates.update', { graduate: form.value.id }), form.value, {
      onSuccess: () => {
        console.log("Graduate updated successfully!");
        emit('close'); // Close the modal
        router.reload(); // Refresh table
      },
      onError: (validationErrors) => {
        errors.value = validationErrors;
      },
    });
  } else {
    // If no ID, create a new graduate
    router.post(route('graduates.store'), form.value, {
      onSuccess: () => {
        console.log("Graduate added successfully!");
        emit('close'); // Close the modal
        router.reload(); // Refresh table
      },
      onError: (validationErrors) => {
        errors.value = validationErrors;
      },
    });
  }
};
</script>
