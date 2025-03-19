<template>
    <dialog ref="dialog" class="z-50 m-0 min-h-full min-w-full overflow-y-auto bg-transparent">
      <div class="bg-white p-6 rounded shadow-lg max-w-lg mx-auto">
        <h2 class="text-xl font-bold mb-4">{{ props.opportunity ? "Edit" : "Add" }} Career Opportunity</h2>
        
        <!-- Program Selection -->
        <div class="mb-4">
          <InputLabel for="program" value="Select Program" />
          <select v-model="form.program_id" id="program" class="w-full p-2 border rounded">
            <option v-for="program in props.programs" :key="program.id" :value="program.id">{{ program.name }}</option>
          </select>
        </div>
  
        <!-- Career Opportunities -->
        <div class="mb-4">
            <InputLabel for="title" value="Career Opportunity Title" />
            <TextInput v-model="form.title" id="title" class="w-full" placeholder="Ex. Nurse, Doctor" />

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
      opportunity: Object, // Receives opportunity data when editing
      programs: Array, // List of programs for selection
  });
  
  const emit = defineEmits(["close", "save"]);
  
  const dialog = ref(null);
  const form = ref({
    program_id: "",
    title: "", // Ensure this is included
});
  
  // Watch for prop changes when opening the modal
  watch(
      () => props.show,
      (newValue) => {
          if (newValue) {
              document.body.style.overflow = "hidden";
              dialog.value?.showModal();
  
              // If editing, pre-fill the form
              if (props.opportunity) {
                  form.value = { ...props.opportunity };
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
        title: form.value.title,
        program_id: form.value.program_id,
    });
};
  </script>