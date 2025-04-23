<template>
    <div v-if="show" class="fixed inset-0 z-50 bg-black bg-opacity-40 flex items-center justify-center">
      <div
        class="bg-white w-full max-w-lg rounded-lg shadow-lg p-6 relative"
        @keydown.esc="$emit('close')"
        tabindex="0"
      >
        <!-- Close -->
        <button @click="$emit('close')" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
          &times;
        </button>
  
        <!-- Title -->
        <div class="mb-4 text-lg font-semibold text-gray-800">
          {{ editing ? 'Edit Degree' : 'Add Degree' }}
        </div>
  
        <!-- Form -->
        <form @submit.prevent="$emit('submit')" class="space-y-5">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Degree Name</label>
            <input type="text" v-model="form.name" placeholder="e.g. BS Computer Science"
                   class="w-full border p-2 rounded" />
            <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
          </div>
  
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Degree Type</label>
            <select v-model="form.type" class="w-full border p-2 rounded">
              <option value="bachelor">Bachelor</option>
              <option value="master">Master</option>
              <option value="doctorate">Doctorate</option>
            </select>
            <p v-if="errors.type" class="text-red-500 text-sm mt-1">{{ errors.type }}</p>
          </div>
  
          <!-- Buttons -->
          <div class="flex justify-end gap-2 pt-4">
            <button type="button" @click="$emit('close')" class="btn-secondary">Cancel</button>
            <button type="submit" class="btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  defineProps({
    show: Boolean,
    editing: Boolean,
    form: Object,
    errors: Object
  })
  defineEmits(['close', 'submit'])
  </script>
  
  <style scoped>
  .btn-primary {
    @apply bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700;
  }
  .btn-secondary {
    @apply bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400;
  }
  </style>
  