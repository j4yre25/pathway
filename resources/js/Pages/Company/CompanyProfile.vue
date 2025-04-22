<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { defineProps } from "vue";

const props = defineProps({
  company: Object,
});
</script>

<template>
  <AppLayout title="Company Profile">
    <!-- Cover Photo Section -->
    <div class="relative bg-gray-800 h-48">
      <img
        :src="company.cover_photo || '/images/default-cover.jpg'"
        alt="Cover Photo"
        class="absolute inset-0 w-full h-full object-cover"
      />
      <div class="absolute inset-0 bg-black bg-opacity-30"></div>
    </div>

    <!-- Company Header -->
    <div class="relative -mt-16 max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-6 flex items-center space-x-6">
                <!-- Logo and Featured Badge -->
                <div class="relative">
                    <img
                        :src="company.logo || '/images/default-logo.png'"
                        alt="Company Logo"
                        class="w-24 h-24 rounded-full object-cover border-4 border-white"
                    />
                    <span
                        v-if="company.is_featured"
                        class="absolute top-0 left-0 bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded-tr-lg rounded-bl-lg"
                    >
                        Featured
                    </span>
                </div>

                <!-- Company Info -->
                <div class="flex-1">
                    <h3 class="text-3xl font-bold text-gray-800">{{ company.company_name }}</h3>
                    <p class="text-gray-600 flex items-center space-x-2">
                        <i class="fas fa-map-marker-alt text-indigo-500"></i>
                        <span>{{ company.address || 'Location not available' }}</span>
                        <a
                            v-if="company.map_link"
                            :href="company.map_link"
                            target="_blank"
                            class="text-indigo-500 hover:underline text-sm"
                        >
                            View on Map
                        </a>
                    </p>
                    <div class="mt-2 flex space-x-4">
                        <a
                            v-for="(link, key) in company.social_links || {}"
                            :key="key"
                            :href="link"
                            target="_blank"
                            class="text-gray-500 hover:text-indigo-500"
                        >
                            <i :class="`fab fa-${key}`"></i>
                        </a>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-4">
                        <a
                            :href="route('profile.show', { id: company.id, edit: 'company' })"
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 inline-block">
                            Edit Profile
                        </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="py-12 bg-gray-100">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        
            <!-- Overview Section -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-800">Date Joined</h4>
                    <p class="text-gray-600">{{ company.founded_date || 'N/A' }}</p>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-800">Sectors</h4>
                    <p class="text-gray-600">{{ company.sector || 'N/A' }}</p>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-800">Posted Jobs</h4>
                    <p class="text-gray-600">{{ company.posted_jobs || 0 }}</p>
                </div>
            </div>

            <!-- Description and Contact Info in Grid -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            
                <!-- Company Description (spans 2 cols on md+) -->
                <div class="md:col-span-2 bg-white shadow-lg rounded-lg p-6">
                    <h4 class="text-xl font-semibold text-gray-800">Company Description</h4>
                    <p class="text-gray-600 mt-4">{{ company.description || 'No description available.' }}</p>
                </div>

                <!-- Contact Information -->
                <div class="bg-white shadow-lg rounded-lg p-6">
                <h4 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4">Contact Information</h4>
                <div class="mt-4 space-y-4">
                    <div class="flex items-center space-x-4">
                    <i class="fas fa-map-marker-alt text-indigo-500 text-lg"></i>
                    <p class="text-gray-600">
                        <strong>Address:</strong> {{ company.address || 'N/A' }}
                    </p>
                    </div>
                    <div class="flex items-center space-x-4">
                    <i class="fas fa-envelope text-indigo-500 text-lg"></i>
                    <p class="text-gray-600">
                        <strong>Email:</strong> {{ company.company_email || 'N/A' }}
                    </p>
                    </div>
                    <div class="flex items-center space-x-4">
                    <i class="fas fa-phone text-indigo-500 text-lg"></i>
                    <p class="text-gray-600">
                        <strong>Mobile Number:</strong> {{ company.company_contact_number || 'N/A' }}
                    </p>
                    </div>
                </div>
</div>
            </div>

            <!-- Team Members Section
            <div v-if="company.team_members && company.team_members.length" class="mt-8 bg-white shadow-lg rounded-lg p-6">
                <h4 class="text-xl font-semibold text-gray-800">Team Members</h4>
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        v-for="member in company.team_members"
                        :key="member.id"
                        class="bg-gray-50 shadow rounded-lg p-4 flex items-center space-x-4">
                        <img
                            :src="member.photo || '/images/default-avatar.png'"
                            alt="Team Member"
                            class="w-16 h-16 rounded-full object-cover"
                        />
                    <div>
                        <h5 class="text-lg font-semibold text-gray-800">{{ member.name }}</h5>
                        <p class="text-sm text-gray-600">{{ member.role }}</p>
                    </div>
                    </div>
                </div>
            </div> -->
            
        </div>
    </div>
  </AppLayout>
</template>
