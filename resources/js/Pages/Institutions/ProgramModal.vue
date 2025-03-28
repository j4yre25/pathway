<template>
    <dialog ref="dialog" class="z-50 m-0 min-h-full min-w-full overflow-y-auto bg-transparent">
        <div class="bg-white p-6 rounded shadow-lg max-w-lg mx-auto">
            <h2 class="text-xl font-bold mb-4">{{ props.program ? "Edit" : "Add" }} Program</h2>

            <!-- Degree Selection -->
            <div class="mb-4">
                <InputLabel for="degree" value="Undergraduate Degree" />
                <select v-model="form.degree" id="degree" class="w-full p-2 border rounded">
                    <option value="">Select Degree</option>
                    <option value="BS">Bachelor's Degree</option>
                    <option value="AS">Associate Degree</option>
                </select>
                <p v-if="errors.degree" class="text-red-500 text-sm">{{ errors.degree }}</p>
            </div>

            <!-- Program Name -->
            <div class="mb-4">
                <InputLabel for="program_name" value="Program Name" />
                <TextInput v-model="form.name" id="program_name" class="w-full" placeholder="Ex. Nursing" />
                <p v-if="errors.name" class="text-red-500 text-sm">{{ errors.name }}</p>
            </div>

            <!-- Error Message -->
            <p v-if="errorMessage" class="text-red-500 text-sm mb-4">{{ errorMessage }}</p>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-2 mt-4">
                <button @click="closeModal" class="px-4 py-2 bg-gray-300 text-gray-700 rounded">Cancel</button>
                <PrimaryButton @click="save">Save</PrimaryButton>
            </div>
        </div>
    </dialog>
</template>

<script setup>
import { ref, watch, computed, defineProps, defineEmits } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    show: Boolean,
    program: Object, // Receives program data when editing
    existingPrograms: Array, // Pass existing programs to check for duplicates
});

const emit = defineEmits(["close", "save"]);

const dialog = ref(null);
const form = ref({
    degree: "",
    name: "",
});

const errors = ref({});
const errorMessage = ref("");

// Computed property to format the program name
const formattedProgramName = computed(() => {
    if (!form.value.degree || !form.value.name) return "";
    return `${form.value.degree} in ${form.value.name}`;
});

// Watch for modal state changes
watch(
    () => props.show,
    (newValue) => {
        if (newValue) {
            document.body.style.overflow = "hidden";
            dialog.value?.showModal();

            // If editing, pre-fill the form
            if (props.program) {
                form.value = { ...props.program };
            } else {
                form.value = { degree: "", name: "" };
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

// Validation & Save
const save = () => {
    errors.value = {};
    errorMessage.value = "";

    if (!form.value.degree) {
        errors.value.degree = "Please select an undergraduate degree.";
    }
    if (!form.value.name.trim()) {
        errors.value.name = "Program name is required.";
    }

    // Check for duplicates
    const duplicate = props.existingPrograms?.some(
        (program) => program.name.toLowerCase() === formattedProgramName.value.toLowerCase()
    );

    if (duplicate) {
        errorMessage.value = "This program already exists!";
        return;
    }

    if (!errors.value.degree && !errors.value.name) {
        emit("save", { name: formattedProgramName.value });
    }
};
</script>
