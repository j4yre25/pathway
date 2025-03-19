<template>
    <dialog ref="dialog" class="z-50 m-0 min-h-full min-w-full overflow-y-auto bg-transparent">
      <div class="bg-white p-6 rounded shadow-lg max-w-lg mx-auto">
        <h2 class="text-xl font-bold mb-4">{{ props.program ? "Edit" : "Add" }} Program</h2>
        
        <!-- Program Name -->
        <div class="mb-4">
          <InputLabel for="program_name" value="Program Name" />
          <TextInput v-model="form.name" id="program_name" class="w-full" placeholder="Ex. BS in Nursing" />
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
      program: Object, // Receives program data when editing
  });
  
  const emit = defineEmits(["close", "save"]);
  
  const dialog = ref(null);
  const form = ref({
      name: "",
  });
  
  // Watch for prop changes when opening the modal
  watch(
      () => props.show,
      (newValue) => {
          if (newValue) {
              document.body.style.overflow = "hidden";
              dialog.value?.showModal();
  
              // If editing, pre-fill the form
              if (props.program) {
                  form.value = { ...props.program };
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
      emit("save", form.value);
  };
  </script>