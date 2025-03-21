<template>
    <dialog ref="dialog" class="z-50 m-0 min-h-full min-w-full overflow-y-auto bg-transparent">
        <div class="bg-white p-6 rounded shadow-lg max-w-lg mx-auto">
            <h2 class="text-xl font-bold mb-4">{{ props.institution ? "Edit" : "Add" }} Institution</h2>
            
            <!-- School Year Range -->
            <div class="mb-4">
                <InputLabel for="school_year_range" value="School Year Range" />
                <TextInput v-model="form.school_year_range" id="school_year_range" class="w-full" placeholder="Ex. 2023-2024" />
               

            </div>

            <!-- Term -->
            <div class="mb-4">
                <TextInput v-model="form.term" id="school_year_range" class="w-full" placeholder="Ex. 1 (Must be from 1 – 5)" />
            </div>

            <!-- Program Selection -->
            <div class="mb-4">
                <InputLabel for="program_id" value="Select Program" />
                <select v-model="form.program_id" id="program_id" class="w-full p-2 border rounded">
                    <option v-for="program in props.programs" :key="program.id" :value="program.id">{{ program.name }}</option>
                </select>
            </div>

            <!-- Career Opportunities -->
            <div class="mb-4">
                <InputLabel for="career_opportunities" value="Career Opportunities" />
                <TextInput v-model="form.career_opportunities" id="career_opportunities" class="w-full" placeholder="Ex. Nurse, Engineer, Teacher (Separate by commas)" />

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
    institution: Object, // Receives institution data when editing
    programs: Array, // List of programs for selection
});

const emit = defineEmits(["close", "save"]);

const dialog = ref(null);
const form = ref({
    school_year_range: "",
    term: "",
    program_id: "", // Updated to program_id
    career_opportunities: "",
});

// Watch for prop changes when opening the modal
watch(
    () => props.show,
    (newValue) => {
        if (newValue) {
            document.body.style.overflow = "hidden";
            dialog.value?.showModal();

            // If editing, pre-fill the form
            if (props.institution) {
                form.value = { ...props.institution };
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
     if (!form.value.school_year_range || !form.value.term || !form.value.program_id) {
        alert("Please fill in all required fields.");
        return;
    }
    emit("save", form.value);
};
</script>