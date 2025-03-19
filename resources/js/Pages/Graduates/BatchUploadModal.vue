<template>
    <div v-if="show" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-xl font-semibold mb-4">Batch Graduates Accounts Creation</h2>
            <p class="text-gray-600 mb-4">Download and fill out the CSV template, then upload it.</p>

            <!-- Download CSV Template -->
            <a :href="'app/Templates/graduate_template.csv'" class="text-blue-600 underline">Download CSV Template</a>

            <!-- File Upload Input -->
            <input type="file" @change="handleFileUpload" accept=".csv" class="mt-4 p-2 border rounded w-full">

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-2 mt-4">
                <button @click="$emit('close')" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                <button @click="uploadCSV" class="px-4 py-2 bg-blue-600 text-white rounded">Upload</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, defineProps, defineEmits } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({ show: Boolean });
const emit = defineEmits(['close']);
const file = ref(null);

const handleFileUpload = (event) => {
    file.value = event.target.files[0];
};

const uploadCSV = () => {
    if (!file.value) {
        alert('Please select a file to upload.');
        return;
    }

    let formData = new FormData();
    formData.append('csv_file', file.value);

    router.post('/graduates/batch-upload', formData, {
        onSuccess: () => {
            emit('close');
            router.reload(); // Refresh the graduate table
        },
        onError: (errors) => {
            console.error(errors);
            alert('Failed to upload the file.');
        },
    });
};
</script>
