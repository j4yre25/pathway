<script setup>
import { ref } from 'vue';

// Reactive data
const activeSection = ref('opportunities');
const newJobPostings = ref(2); 

// Method to set the active section
const setActiveSection = (section) => {
  activeSection.value = section;
};
</script>

<template>
    <div class="bg-gray-100 min-h-screen p-6">
        <div class="container mx-auto">
        <h1 class="text-3xl font-bold mb-4">Job Inbox</h1>
        <p class="text-gray-600 mb-6">View and manage job opportunities and applications</p>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex border-b border-gray-200 mb-6">
            <button 
                class="py-2 px-4" 
                :class="{'text-indigo-600 border-b-2 border-indigo-600': activeSection === 'opportunities'}"
                @click="setActiveSection('opportunities')"
            >
                Opportunities
            </button>
            <button 
                class="py-2 px-4" 
                :class="{'text-indigo-600 border-b-2 border-indigo-600': activeSection === 'applications'}"
                @click="setActiveSection('applications')"
            >
                Applications
            </button>
            <button 
            class="py-2 px-4 relative" 
            :class="{'text-indigo-600 border-b-2 border-indigo-600': activeSection === 'notifications'}"
            @click="setActiveSection('notifications')"
          >
            Notifications
            <!-- Notification Badge -->
            <span 
              v-if="newJobPostings > 0" 
              class="absolute top-0 right-0 mt-1 mr-1 bg-red-500 text-white text-xs rounded-full px-1.5"
            >
              {{ newJobPostings }}
            </span>
          </button>
        </div>

        <!-- Filter and Notification Bell Icons -->
        <div class="flex items-center space-x-4 mb-6">
          <!-- Filter Button -->
          <button class="relative px-4 py-2 bg-white rounded-md shadow-sm text-gray-700 font-medium">
            <i class="fas fa-filter"></i> Filter
          </button>
          <!-- Notification Bell Button -->
          <button 
            class="relative px-4 py-2 bg-white rounded-md shadow-sm text-gray-700 font-medium"
            @click="setActiveSection('notifications')"
          >
            <i class="fas fa-bell"></i>
            <!-- Notification Badge -->
            <span 
              v-if="newJobPostings > 0" 
              class="absolute top-0 right-0 mt-1 mr-1 bg-red-500 text-white text-xs rounded-full px-1.5"
            >
              {{ newJobPostings }}
            </span>
          </button>
        </div>

            <!-- Opportunities Section -->
            <div v-if="activeSection === 'opportunities'">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-4">
                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
                    <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">{{ newJobOne }} <span class="ml-2 text-sm text-green-600 bg-green-100 px-2 py-1 rounded-full">New</span></h2>
                    <div class="text-right">
                        <span class="text-2xl font-bold text-indigo-600">{{ newJobOnePercent }}</span>
                        <p class="text-sm text-gray-500">Match</p>
                    </div>
                    </div>
                    <p class="text-gray-600 mb-2">{{ CompanyName }}</p>
                    <div class="flex items-center text-gray-600 mb-2">
                    <i class="fas fa-map-marker-alt mr-2"></i> {{ CompanyLocation }} (Remote)
                    <i class="fas fa-map-marker-alt mr-2"></i> {{ JobLocation }} {{ JobType }}
                    </div>
                    <div class="flex items-center text-gray-600 mb-2">
                    <i class="fas fa-dollar-sign mr-2"></i> {{ SalaryRange }}
                    </div>
                    <div class="flex items-center text-gray-600 mb-2">
                    <i class="fas fa-clock mr-2"></i> {{ JobType }}
                    </div>
                    <div class="flex items-center text-gray-600 mb-4">
                    <i class="fas fa-calendar-alt mr-2"></i> Posted 2 days ago
                    </div>
                    <p class="text-gray-700 mb-4">{{ JobDescription }}></p>
                    <div class="flex space-x-4">
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-md">View</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md">Apply</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md">Archive</button>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
                    <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Frontend Developer <span class="ml-2 text-sm text-blue-600 bg-blue-100 px-2 py-1 rounded-full">Viewed</span></h2>
                    <div class="text-right">
                        <span class="text-2xl font-bold text-indigo-600">86%</span>
                        <p class="text-sm text-gray-500">Match</p>
                    </div>
                    </div>
                    <p class="text-gray-600 mb-2">Digital Solutions LLC</p>
                    <div class="flex items-center text-gray-600 mb-2">
                    <i class="fas fa-map-marker-alt mr-2"></i> New York, NY (Hybrid)
                    </div>
                    <div class="flex items-center text-gray-600 mb-2">
                    <i class="fas fa-dollar-sign mr-2"></i> $90,000 - $120,000
                    </div>
                    <div class="flex items-center text-gray-600 mb-2">
                    <i class="fas fa-clock mr-2"></i> Full-time
                    </div>
                    <div class="flex items-center text-gray-600 mb-4">
                    <i class="fas fa-calendar-alt mr-2"></i> Posted 1 week ago
                    </div>
                    <p class="text-gray-700 mb-4">Join our frontend team to create beautiful and responsive interfaces for our enterprise clients. Experience with React, TypeScript, and modern CSS frameworks required.</p>
                    <div class="flex space-x-4">
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-md">View</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md">Apply</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md">Archive</button>
                    </div>
                </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4">Inbox Summary</h2>
                <p class="text-gray-600 mb-4">Overview of your job opportunities</p>
                <div class="flex justify-between items-center mb-4">
                    <div class="text-center">
                    <i class="fas fa-briefcase text-2xl text-indigo-600 mb-2"></i>
                    <p class="text-lg font-bold">5</p>
                    <p class="text-gray-600">Total Jobs</p>
                    </div>
                    <div class="text-center">
                    <i class="fas fa-bell text-2xl text-green-600 mb-2"></i>
                    <p class="text-lg font-bold">2</p>
                    <p class="text-gray-600">New</p>
                    </div>
                </div>
                <h3 class="text-lg font-bold mb-2">Match Breakdown</h3>
                <div class="mb-2">
                    <p class="text-gray-600">Very High (90%+)</p>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
                    <div class="bg-green-500 h-2.5 rounded-full" style="width: 40%"></div>
                    </div>
                </div>
                <div class="mb-2">
                    <p class="text-gray-600">High (80-89%)</p>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
                    <div class="bg-blue-500 h-2.5 rounded-full" style="width: 40%"></div>
                    </div>
                </div>
                <div class="mb-4">
                    <p class="text-gray-600">Medium (70-79%)</p>
                    <div class="w-full bg-gray-2 00 rounded-full h-2.5 mb-2">
                    <div class="bg-orange-500 h-2.5 rounded-full" style="width: 20%"></div>
                    </div>
                </div>
                <h3 class="text-lg font-bold mb-2">Job Type Preferences</h3>
                <div class="flex flex-wrap gap-2">
                    <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md">Full-time</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md">Remote</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md">Hybrid</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md">Contract</button>
                </div>
                </div>
            </div>
            </div>
        </div>

        <!-- Applications Section -->
        <div v-if="activeSection === 'applications'">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold mb-4">Your Applications</h2>
                    <div class="bg-gray-100 text-gray-800">
                    <div class="container mx-auto p-4">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <div class="lg:col-span-2">
                            <div class="bg-white p-4 rounded-lg shadow">
                            <h2 class="text-xl font-semibold mb-4">Application Status Tracking</h2>
                            <p class="text-gray-600 mb-4">Track the progress of your job applications</p>
                            <div class="bg-indigo-100 p-4 rounded-lg mb-4">
                                <div class="flex justify-between items-center mb-2">
                                <div>
                                    <h3 class="text-lg font-semibold">Senior Full Stack Developer</h3>
                                    <p class="text-gray-600">Tech Innovations Inc.</p>
                                </div>
                                <a href="#" class="text-indigo-600">Details</a>
                                </div>
                                <div class="relative pt-1">
                                <div class="flex mb-2 items-center justify-between">
                                    <div>
                                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-indigo-600 bg-indigo-200">
                                        Applied
                                    </span>
                                    </div>
                                    <div class="text-right">
                                    <span class="text-xs font-semibold inline-block text-indigo-600">
                                        Interview
                                    </span>
                                    </div>
                                </div>
                                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-indigo-200">
                                    <div style="width: 50%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-600"></div>
                                </div>
                                </div>
                                <div class="space-y-2">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                    <div>
                                    <p class="text-sm font-semibold">Application Submitted</p>
                                    <p class="text-xs text-gray-600">May 15, 2023 at 2:30 PM</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                    <div>
                                    <p class="text-sm font-semibold">Application Reviewed</p>
                                    <p class="text-xs text-gray-600">May 18, 2023 at 10:15 AM</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-check-circle text-indigo-500"></i>
                                    <div>
                                    <p class="text-sm font-semibold">Shortlisted for Interview</p>
                                    <p class="text-xs text-gray-600">May 20, 2023 at 9:45 AM</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-clock text-gray-500"></i>
                                    <div>
                                    <p class="text-sm font-semibold">Interview Scheduled</p>
                                    <p class="text-xs text-gray-600">Scheduled for May 25, 2023 at 2:00 PM</p>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow mb-4">
                                <div class="flex justify-between items-center mb-2">
                                <div>
                                    <h3 class="text-lg font-semibold">Backend Engineer</h3>
                                    <p class="text-gray-600">Data Systems Co.</p>
                                    <p class="text-gray-600"><i class="fas fa-map-marker-alt"></i> Austin, TX</p>
                                </div>
                                <a href="#" class="text-gray-600">View Details</a>
                                </div>
                                <div class="relative pt-1">
                                <div class="flex mb-2 items-center justify-between">
                                    <div>
                                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-indigo-600 bg-indigo-200">
                                        Applied
                                    </span>
                                    </div>
                                    <div class="text-right">
                                    <span class="text-xs font-semibold inline-block text-indigo-600">
                                        Review
                                    </span>
                                    </div>
                                </div>
                                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-indigo-200">
                                    <div style="width: 25%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-600"></div>
                                </div>
                                </div>
                                <p class="text-xs text-gray-600">Applied 5 days ago</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow">
                                <div class="flex justify-between items-center mb-2">
                                <div>
                                    <h3 class="text-lg font-semibold">Frontend Developer</h3>
                                    <p class="text-gray-600">Creative Digital</p>
                                </div>
                                <a href="#" class="text-gray-600">View Details</a>
                                </div>
                                <div class="relative pt-1">
                                <div class="flex mb-2 items-center justify-between">
                                    <div>
                                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-indigo-600 bg-indigo-200">
                                        Applied
                                    </span>
                                    </div>
                                    <div class="text-right">
                                    <span class="text-xs font-semibold inline-block text-indigo-600">
                                        Review
                                    </span>
                                    </div>
                                </div>
                                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-indigo-200">
                                    <div style="width: 25%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-600"></div>
                                </div>
                                </div>
                                <p class="text-xs text-gray-600">Applied 3 days ago</p>
                            </div>
                            </div>
                        </div>
                        <div class="lg:col-span-1">
                            <div class="bg-white p-4 rounded-lg shadow mb-4">
                            <h2 class="text-xl font-semibold mb-4">Application Overview</h2>
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="bg-gray-100 p-4 rounded-lg text-center">
                                <p class="text-2xl font-semibold">3</p>
                                <p class="text-gray-600">Active Applications</p>
                                </div>
                                <div class="bg-gray-100 p-4 rounded-lg text-center">
                                <p class="text-2xl font-semibold">1</p>
                                <p class="text-gray-600">Interview Scheduled</p>
                                </div>
                                <div class="bg-gray-100 p-4 rounded-lg text-center">
                                <p class="text-2xl font-semibold">8</p>
                                <p class="text-gray-600">All-Time Applications</p>
                                </div>
                                <div class="bg-gray-100 p-4 rounded-lg text-center">
                                <p class="text-2xl font-semibold">2</p>
                                <p class="text-gray-600">Offers Received</p>
                                </div>
                            </div>
                            <h3 class="text-lg font-semibold mb-2">Applications by Role</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                <p class="text-gray-600">Full Stack Developer</p>
                                <div class="flex items-center space-x-2">
                                    <p class="text-gray-600">12</p>
                                    <span class="bg-green-200 text-green-600 rounded-full px-2 py-1 text-xs">+5</span>
                                </div>
                                </div>
                                <div class="flex justify-between items-center">
                                <p class="text-gray-600">Frontend Developer</p>
                                <div class="flex items-center space-x-2">
                                    <p class="text-gray-600">8</p>
                                    <span class="bg-green-200 text-green-600 rounded-full px-2 py-1 text-xs">+2</span>
                                </div>
                                </div>
                                <div class="flex justify-between items-center">
                                <p class="text-gray-600">Backend Engineer</p>
                                <div class="flex items-center space-x-2">
                                    <p class="text-gray-600">10</p>
                                    <span class="bg-green-200 text-green-600 rounded-full px-2 py-1 text-xs">+3</span>
                                </div>
                                </div>
                                <div class="flex justify-between items-center">
                                <p class="text-gray-600">DevOps Specialist</p>
                                <div class="flex items-center space-x-2">
                                    <p class="text-gray-600">5</p>
                                    <span class="bg-green-200 text-green-600 rounded-full px-2 py-1 text-xs">+1</span>
                                </div>
                                </div>
                            </div>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow">
                            <h2 class="text-xl font-semibold mb-4">Application Tips</h2>
                            <div class="space-y-4">
                                <div class="bg-blue-100 p-4 rounded-lg">
                                <h3 class="text-blue-600 font-semibold">Personalize Your Resume</h3>
                                <p class="text-gray-600">Tailor your resume to each job application to highlight relevant skills and experience.</p>
                                </div>
                                <div class="bg-indigo-100 p-4 rounded-lg">
                                <h3 class="text-indigo-600 font-semibold">Follow Up</h3>
                                <p class="text-gray-600">Send a follow-up email one week after applying if you haven't heard back.</p>
                                </div>
                                <div class="bg-yellow-100 p-4 rounded-lg">
                                <h3 class="text-yellow-600 font-semibold">Research the Company</h3>
                                <p class="text-gray-600">Show your knowledge about the company during interviews to demonstrate your interest.</p>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>

            <!-- Notifications Section -->
            <div v-if="activeSection === 'notifications'">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-4">
                    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold">Recent Notifications</h2>
                        <p class="text-gray-600">Stay updated on your application status</p>
                    </div>
                    <button class="text-sm text-blue-600 hover:underline">Mark all as read</button>
                    </div>

                    <!-- Notification Items -->
                    <div class="space-y-4">
                    <div class="bg-blue-50 p-4 rounded-lg flex items-center space-x-4">
                        <i class="fas fa-bell text-blue-500"></i>
                        <div>
                        <p class="text-gray-800">Your application for Full Stack Developer has been viewed</p>
                        <p class="text-gray-500 text-sm">2 hours ago</p>
                        </div>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg flex items-center space-x-4">
                        <i class="fas fa-bell text-blue-500"></i>
                        <div>
                        <p class="text-gray-800">You have been shortlisted for the Frontend Position</p>
                        <p class="text-gray-500 text-sm">1 day ago</p>
                        </div>
                    </div>
                    <div class="p-4 rounded-lg flex items-center space-x-4">
                        <i class="fas fa-bell text-gray-500"></i>
                        <div>
                        <p class="text-gray-800">Tech Solutions Inc. sent you a message</p>
                        <p class="text-gray-500 text-sm">2 days ago</p>
                        </div>
                    </div>
                    </div>

                    <!-- View All Notifications -->
                    < div class="mt-4 text-right">
                    <a href="#" class="text-blue-600 hover:underline">View all notifications</a>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</template>