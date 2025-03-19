<template>
    <dialog ref="dialog" class="z-50 m-0 min-h-full min-w-full overflow-y-auto bg-transparent">
      <div class="bg-white p-6 rounded shadow-lg max-w-lg mx-auto">
        <h2 class="text-xl font-bold mb-4">{{ props.year ? "Edit" : "Add" }} School Year</h2>
        
        <!-- School Year Range -->
        <div class="mb-4">
          <InputLabel for="school_year_range" value="School Year Range" />
          <TextInput v-model="form.school_year_range" id="school_year_range" class="w-full" placeholder="Ex. 2023-2024" />

        </div>
  
        <!-- Term -->
        <div class="mb-4">
          <InputLabel for="term" value="Term" />
          <TextInput v-model="form.term" id="term" class="w-full" placeholder="Ex. 1 (Must be from 1 – 5)" />
        </div>
  
        <!-- Action Buttons -->
        <div class="flex justify-end space-x-2 mt-4">
          <button @click="closeModal" class="px-4 py-2 bg-gray-300 text-gray-700 rounded">Cancel</button>
          <PrimaryButton @click="save">Save</PrimaryButton>
        </div>
      </div>
    </dialog>
  </template>
  <script setup>
  import { ref, watch, defineProps, defineEmits } from "vue";
  import PrimaryButton from "@/Components/PrimaryButton.vue";
  import InputLabel from "@/Components/InputLabel.vue";
  import TextInput from "@/Components/TextInput.vue";
  
  const props = defineProps({
      show: Boolean,
      year: Object, // Receives year data when editing
  });
  
  const emit = defineEmits(["close", "save"]);
  
  const dialog = ref(null);
  const form = ref({
      school_year_range: "",
      term: "",
  });
  
  // Watch for prop changes when opening the modal
  watch(
      () => props.show,
      (newValue) => {
          if (newValue) {
              document.body.style.overflow = "hidden";
              dialog.value?.showModal();
  
              // If editing, pre-fill the form
              if (props.year) {
                  form.value = { ...props.year };
              }
          } else {
              document.body.style.overflow = null;
              dialog.value?.close();
          }
      }
  );
  
  const closeModal = () => {
      emit("close");
  };
  
  const save = () => {
    emit("save", {
        school_year_range: form.value.school_year_range,
        term: form.value.term,
    });
};
  </script>