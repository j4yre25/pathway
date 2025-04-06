<script setup>
import Graduate from '@/Layouts/AppLayout.vue';
import { ref, computed } from 'vue';
import Modal from '@/Components/Modal.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import TextArea from '@/Components/TextArea.vue';
import SelectInput from '@/Components/SelectInput.vue';
import axios from 'axios';
import PrimaryButton from '@/Components/PrimaryButton.vue';
// import Datepicker from 'vue3-datepicker';
// import '@fortawesome/fontawesome-free/css/all.css';

// Active section state
const activeSection = ref('general');

// Function to set the active section
const setActiveSection = (section) => {
  activeSection.value = section;
};

// Profile data
const profile = ref({
  fullName: '',
  graduate_professional_title: '',
  graduate_email: '',
  graduate_phone: '',
  graduate_location: '',
  graduate_birthdate: '',
  graduate_gender: '',
  graduate_ethnicity: '',
  graduate_address: '',
  graduate_about_me: '',
  graduate_picture_url: 'path/to/default/image.jpg'
});

// Modal states
const isProfileModalOpen = ref(false);
const isPasswordModalOpen = ref(false);
const isAddEducationModalOpen = ref(false);
const isUpdateEducationModalOpen = ref(false);
const isEducationAddedModalOpen = ref(false);
const isEducationUpdatedModalOpen = ref(false);
const isAddSkillModalOpen = ref(false);
const isSkillsAddedModalOpen = ref(false);
const isAddExperienceModalOpen = ref(false);
const isUpdateExperienceModalOpen = ref(false);
let currentExperienceIndex = ref(null);
const isAddCertificationModalOpen = ref(false);
const isAddAchievementModalOpen = ref(false);
const isUpdateAchievementModalOpen = ref(false);
let currentAchievementIndex = ref(null);

// Education data
const educationEntries = ref([]);
const education = ref({
  graduate_education_institution_id: '',
  graduate_education_program: '',
  graduate_education_field_of_study: '',
  graduate_education_start_date: null,
  graduate_education_end_date: null,
  graduate_education_description: ''
});

// Experience data
const experienceEntries = ref([]); // Array to hold experience entries
const experience = ref({
  graduate_experience_title: '',
  graduate_experience_company: '',
  graduate_experience_start_date: null,
  graduate_experience_end_date: null,
  graduate_experience_address: '',
  graduate_experience_achievements: '',
  graduate_ex极perience_skills_tech: ''
});

// Password data
const currentPassword = ref('');
const newPassword = ref('');
const confirmPassword = ref('');

// Skill data
const skills = ref([]);
const skillName = ref('');
const proficiencyLevel = ref(0);
const skillType = ref('');
const yearsExperience = ref(0);
const isUpdateSkillModalOpen = ref(false);
let currentSkillIndex = ref(null);
const noExpiryDate = ref(false);

// Achievement data
const achievementEntries = ref([]); // Array to hold achievement entries
const achievement = ref({
  graduate_achievement_title: '',
  graduate_achievement_issuer: '',
  graduate_achievement_date: null,
  graduate_achievement_description: '',
  graduate_achievement_url: '',
  graduate_achievement_type: ''
});

// File upload handler
const onFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      profile.value.graduate_picture_url = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

// Save profile handler
const saveProfile = () => {
  console.log('Profile saved:', profile.value);
  isProfileModalOpen.value = true; // Open Profile Information modal
};

// Change password handler
const handleSubmit = () => {
  console.log('Current Password:', currentPassword.value);
  console.log('New Password:', newPassword.value);
  console.log('Confirm Password:', confirmPassword.value);
  isPasswordModalOpen.value = true; // Open Password Change modal
};

// Add education handler
const addEducation = () => {
  if (education.value.graduate_education_institution_id && education.value.graduate_education_program && education.value.graduate_education_field_of_study) {
    educationEntries.value.push({
      id: Date.now(),
      graduate_education_institution_id: education.value.graduate_education_institution_id,
      graduate_education_program: education.value.graduate_极education_program,
      graduate_education_field_of_study: education.value.graduate_education_field_of_study,
      graduate_education_start_date: education.value.graduate_education_start_date,
      graduate_education_end_date: education.value.graduate_education_end_date,
      graduate_education_description: education.value.graduate_education_description
    });
    education.value = {
      graduate_education_institution_id: '',
      graduate_education_program: '',
      graduate_education_field_of_study: '',
      graduate_education_start_date: null,
      graduate_education_end_date: null,
      graduate_education_description: ''
    };
    isAddEducationModalOpen.value = false; // Close the Add Education modal
    isEducationAddedModalOpen.value = true; // Open the Education Added success modal
  } else {
    alert("Please fill in all required fields.");
  }
};

// Add experience handler
const addExperience = () => {
  if (
    experience.value.graduate_experience_title &&
    experience.value.graduate_experience_company &&
    experience.value.graduate_experience_start_date &&
    experience.value.graduate_experience_end_date &&
    experience.value.graduate_experience_address &&
    experience.value.graduate_experience_achievements &&
    experience.value.graduate_experience_skills_tech
  ) {
    experienceEntries.value.push({
      id: Date.now(),
      graduate_experience_title: experience.value.graduate_experience_title,
      graduate_experience_company: experience.value.graduate_experience_company,
      graduate_experience_start_date: experience.value.graduate_experience_start_date,
      graduate_experience_end_date: experience.value.graduate_experience_end_date,
      graduate_experience_address: experience.value.graduate_experience_address,
      graduate_experience_achievements: experience.value.graduate_experience_achievements,
      graduate_experience_skills_tech: experience.value.graduate_experience_skills_tech
    });

    // Reset the experience object
    resetExperience();

    isAddExperienceModalOpen.value = false; // Close the Add Experience modal
  } else {
    alert("Please fill in all required fields.");
  }
};

// Add achievement handler
const addAchievement = () => {
  if (
    achievement.value.graduate_achievement_title &&
    achievement.value.graduate_achievement_issuer &&
    achievement.value.graduate_achievement_date
  ) {
    achievementEntries.value.push({
      id: Date.now(),
      graduate_achievement_title: achievement.value.graduate_achievement_title,
      graduate_achievement_issuer: achievement.value.graduate_achievement_issuer,
      graduate_achievement_date: achievement.value.graduate_achievement_date,
      graduate_achievement_description: achievement.value.graduate_achievement_description,
      graduate_achievement_url: achievement.value.graduate_achievement_url,
      graduate_achievement_type: achievement.value.graduate_achievement_type
    });

    resetAchievement();
    isAddAchievementModalOpen.value = false; // Close the Add Achievement modal
  } else {
    alert("Please fill in all required fields.");
  }
};


// Update experience handler
const updateExperience = () => {
  const index = currentExperienceIndex.value;
  if (
    experience.value.graduate_experience_title &&
    experience.value.graduate_experience_company &&
    experience.value.graduate_experience_start_date &&
    experience.value.graduate_experience_end_date &&
    experience.value.graduate_experience_address &&
    experience.value.graduate_experience_achievements &&
    experience.value.graduate_experience_skills_tech
  ) {
    // Create a new experience entry with the updated values
    const updatedExperience = {
      id: experienceEntries.value[index].id, // Keep the same ID
      graduate_experience_title: experience.value.graduate_experience_title,
      graduate_experience_company: experience.value.graduate_experience_company,
      graduate_experience_start_date: experience.value.graduate_experience_start_date,
      graduate_experience_end_date: experience.value.graduate_experience_end_date,
      graduate_experience_address: experience.value.graduate_experience_address,
      graduate_experience_achievements: experience.value.graduate_experience_achievements,
      graduate_experience_skills_tech: experience.value.graduate_experience_skills_tech
    };

    // Update the experience entry in the array
    experienceEntries.value[index] = updatedExperience;

    // Reset the experience object
    resetExperience();

    isUpdateExperienceModalOpen.value = false; // Close the Update Experience modal
  } else {
    alert("Please fill in all required fields.");
  }
};

// Update achievement handler
const updateAchievement = () => {
  const index = currentAchievementIndex.value;
  if (
    achievement.value.graduate_achievement_title &&
    achievement.value.graduate_achievement_issuer &&
    achievement.value.graduate_achievement_date
  ) {
    const updatedAchievement = {
      id: achievementEntries.value[index].id, // Keep the same ID
      graduate_achievement_title: achievement.value.graduate_achievement_title,
      graduate_achievement_issuer: achievement.value.graduate_achievement_issuer,
      graduate_achievement_date: achievement.value.graduate_achievement_date,
      graduate_achievement_description: achievement.value.graduate_achievement_description,
      graduate_achievement_url: achievement.value.graduate_achievement_url,
      graduate_achievement_type: achievement.value.graduate_achievement_type
    };

    achievementEntries.value[index] = updatedAchievement;
    resetAchievement();
    isUpdateAchievementModalOpen.value = false; // Close the Update Achievement modal
  } else {
    alert("Please fill in all required fields.");
  }
};

// Reset experience object
const resetExperience = () => {
  experience.value = {
    graduate_experience_title: '',
    graduate_experience_company: '',
    graduate_experience_start_date: null,
    graduate_experience_end_date: null,
    graduate_experience_address: '',
    graduate_experience_achievements: '',
    graduate_experience_skills_tech: ''
  };
};

// Reset achievement object
const resetAchievement = () => {
  achievement.value = {
    graduate_achievement_title: '',
    graduate_achievement_issuer: '',
    graduate_achievement_date: null,
    graduate_achievement_description: '',
    graduate_achievement_url: '',
    graduate_achievement_type: ''
  };
};

const openUpdateExperienceModal = (entry, index) => {
  experience.value = { ...entry };
  currentExperienceIndex.value = index;
  isUpdateExperienceModalOpen.value = true;
};

// Certification data
const certificationEntries = ref([]); // Array to hold certification entries
const certification = ref({
  graduate_certification_name: '',
  graduate_certification_issuer: '',
  graduate_certification_issue_date: null,
  graduate_certification_expiry_date: null,
  graduate_certification_credential_id: '',
  graduate_certification_credential_url: ''
});

// Add certification handler
const addCertification = () => {
  if (
    certification.value.graduate_certification_name &&
    certification.value.graduate_certification_issuer &&
    certification.value.graduate_certification_issue_date
  ) {
    // If "No expiry date" is checked, set expiry date to null
    if (noExpiryDate.value) {
      certification.value.graduate_certification_expiry_date = null;
    }

    certificationEntries.value.push({
      id: Date.now(),
      graduate_certification_name: certification.value.graduate_certification_name,
      graduate_certification_issuer: certification.value.graduate_certification_issuer,
      graduate_certification_issue_date: certification.value.graduate_certification_issue_date,
      graduate_certification_expiry_date: certification.value.graduate_certification_expiry_date,
      graduate_certification_credential_id: certification.value.graduate_certification_credential_id,
      graduate_certification_credential_url: certification.value.graduate_certification_credential_url
    });

    resetCertification();
    noExpiryDate.value = false; // Reset the checkbox
    isAddCertificationModalOpen.value = false; // Close the Add Certification modal
  } else {
    alert("Please fill in all required fields.");
  }
};

// Reset certification object
const resetCertification = () => {
  certification.value = {
    graduate_certification_name: '',
    graduate_certification_issuer: '',
    graduate_certification_issue_date: null,
    graduate_certification_expiry_date: null,
    graduate_certification_credential_id: '',
    graduate_certification_credential_url: ''
  };
};

// Close add certification modal
const closeAddCertificationModal = () => {
  isAddCertificationModalOpen.value = false;
  resetCertification(); // Reset the certification object
  noExpiryDate.value = false; // Reset the checkbox
};


// Close modals
const closeProfileModal = () => {
  isProfileModalOpen.value = false;
};

const closePasswordModal = () => {
  isPasswordModalOpen.value = false;
};

const closeAddEducationModal = () => {
  isAddEducationModalOpen.value = false;
};

const closeUpdateEducationModal = () => {
  isUpdateEducationModalOpen.value = false;
};

const closeEducationAddedModal = () => {
  isEducationAddedModalOpen.value = false;
};

const closeEducationUpdatedModal = () => {
  isEducationUpdatedModalOpen.value = false;
};

const closeSkillsAddedModal = () => {
  isSkillsAddedModalOpen.value = false;
};

const closeAddExperienceModal = () => {
  isAddExperienceModalOpen.value = false;
};

const closeUpdateExperienceModal = () => {
  isUpdateExperienceModalOpen.value = false;
  resetExperience(); // Reset the experience object
};

// Remove education handler
const removeEducation = (id) => {
  educationEntries.value = educationEntries.value.filter(entry => entry.id !== id);
};

// Remove experience handler
const removeExperience = (id) => {
  experienceEntries.value = experienceEntries.value.filter(entry => entry.id !== id);
};

// Save Skill Handler
const saveSkill = () => {
  if (proficiencyLevel.value < 1 || proficiencyLevel.value > 100) {
    alert("Proficiency level must be between 1 and 100.");
    return;
  }

  const skillExists = skills.value.some(skill => skill.graduate_skills_name.toLowerCase() === skillName.value.toLowerCase());
  if (skillExists) {
    alert("This skill already exists. Please enter a different skill.");
    return;
  }

  skills.value.push({
    graduate_skills_name: skillName.value,
    graduate_skills_proficiency: proficiencyLevel.value,
    graduate_skills_type: skillType.value,
    graduate_skills_years_experience: yearsExperience.value
  });

  closeAddSkillModal();
};

// Close Add Skill Modal
const closeAddSkillModal = () => {
  isAddSkillModalOpen.value = false;
  skillName.value = '';
  proficiencyLevel.value = 0;
  skillType.value = '';
  yearsExperience.value = 0;
};

// Open Add Skill Modal
const openAddSkillModal = () => {
  isAddSkillModalOpen.value = true;
};

// Close add achievement modal
const closeAddAchievementModal = () => {
  isAddAchievementModalOpen.value = false;
  resetAchievement();
};

// Method to remove a skill
const removeSkill = (skill) => {
  if (confirm(`Are you sure you want to delete the skill: ${skill.graduate_skills_name}?`)) {
    skills.value = skills.value.filter(s => s.graduate_skills_name !== skill.graduate_skills_name);
  }
};

// Method to open the update skill modal
const editSkill = (skill) => {
  skillName.value = skill.graduate_skills_name;
  proficiencyLevel.value = skill.graduate_skills_proficiency;
  skillType.value = skill.graduate_skills_type;
  yearsExperience.value = skill.graduate_skills_years_experience;
  currentSkillIndex.value = skills.value.indexOf(skill);
  isUpdateSkillModalOpen.value = true;
};

// Method to update the skill
const updateSkill = () => {
  if (proficiencyLevel.value < 1 || proficiencyLevel.value > 100) {
    alert("Proficiency level must be between 1 and 100.");
    return;
  }

  skills.value[currentSkillIndex.value] = {
    graduate_skills_name: skillName.value,
    graduate_skills_proficiency: proficiencyLevel.value,
    graduate_skills_type: skillType.value,
    graduate_skills_years_experience: yearsExperience.value
  };

  closeUpdateSkillModal();
};

// Close update skill modal
const closeUpdateSkillModal = () => {
  isUpdateSkillModalOpen.value = false;
  skillName.value = '';
  proficiencyLevel.value = 0;
  skillType.value = '';
  yearsExperience.value = 0;
};

// Close update achievement modal
const closeUpdateAchievementModal = () => {
  isUpdateAchievementModalOpen.value = false;
  resetAchievement();
};

// Computed property to group skills by type
const groupedSkills = computed(() => {
  return skills.value.reduce((acc, skill) => {
    const type = skill.graduate_skills_type;
    if (!acc[type]) {
      acc[type] = [];
    }
    acc[type].push(skill);
    return acc;
  }, {});
});

// Open update education modal
const openUpdateEducationModal = (educationData) => {
  education.value = { ...educationData };
  isUpdateEducationModalOpen.value = true;
};

// Open update achievement modal
const openUpdateAchievementModal = (entry, index) => {
  achievement.value = { ...entry };
  currentAchievementIndex.value = index;
  isUpdateAchievementModalOpen.value = true;
};

// Computed property to filter skills based on selected type
const filteredSkills = computed(() => {
  if (!skillType.value) return skills.value;
  return skills.value.filter(skill => skill.graduate_skills_type === skillType.value);
});     

// Testimonials Data
const testimonialsEntries = ref([]); // Array to hold testimonials entries
const testimonials = ref({
  graduate_testimonials_name: '',
  graduate_testimonials_role_title: '',
  graduate_testimonials_relationship: '',
  graduate_testimonials_testimonial: ''
});

// Save Career Goals Handler
const saveCareerGoals = () => {
  console.log('Career Goals saved:', careerGoals.value);
};

// Modal states for testimonials
const isAddTestimonialsModalOpen = ref(false);
const isUpdateTestimonialsModalOpen = ref(false);
let currentTestimonialsIndex = ref(null);

// Add testimonials handler
const addTestimonials = () => {
  if (
    testimonials.value.graduate_testimonials_name &&
    testimonials.value.graduate_testimonials_role_title &&
    testimonials.value.graduate_testimonials_relationship &&
    testimonials.value.graduate_testimonials_testimonial
  ) {
    testimonialsEntries.value.push({
      id: Date.now(),
      graduate_testimonials_name: testimonials.value.graduate_testimonials_name,
      graduate_testimonials_role_title: testimonials.value.graduate_testimonials_role_title,
      graduate_testimonials_relationship: testimonials.value.graduate_testimonials_relationship,
      graduate_testimonials_testimonial: testimonials.value.graduate_testimonials_testimonial
    });

    resetTestimonials();
    isAddTestimonialsModalOpen.value = false; // Close the Add Testimonials modal
  } else {
    alert("Please fill in all required fields.");
  }
};

// Reset testimonials object
const resetTestimonials = () => {
  testimonials.value = {
    graduate_testimonials_name: '',
    graduate_testimonials_role_title: '',
    graduate_testimonials_relationship: '',
    graduate_testimonials_testimonial: ''
  };
};

// Update testimonials handler
const updateTestimonials = () => {
  const index = currentTestimonialsIndex.value;
  if (
    testimonials.value.graduate_testimonials_name &&
    testimonials.value.graduate_testimonials_role_title &&
    testimonials.value.graduate_testimonials_relationship &&
    testimonials.value.graduate_testimonials_testimonial
  ) {
    const updatedTestimonials = {
      id: testimonialsEntries.value[index].id, // Keep the same ID
      graduate_testimonials_name: testimonials.value.graduate_testimonials_name,
      graduate_testimonials_role_title: testimonials.value.graduate_testimonials_role_title,
      graduate_testimonials_relationship: testimonials.value.graduate_testimonials_relationship,
      graduate_testimonials_testimonial: testimonials.value.graduate_testimonials_testimonial
    };

    testimonialsEntries.value[index] = updatedTestimonials;
    resetTestimonials();
    isUpdateTestimonialsModalOpen.value = false; // Close the Update Testimonials modal
  } else {
    alert("Please fill in all required fields.");
  }
};

// Open update testimonials modal
const openUpdateTestimonialsModal = (entry, index) => {
  testimonials.value = { ...entry };
  currentTestimonialsIndex.value = index;
  isUpdateTestimonialsModalOpen.value = true;
};

// Remove testimonials handler
const removeTestimonials = (id) => {
  testimonialsEntries.value = testimonialsEntries.value.filter(entry => entry.id !== id);
};

// Close add testimonials modal
const closeAddTestimonialsModal = () => {
  isAddTestimonialsModalOpen.value = false;
  resetTestimonials();
};

// Close update testimonials modal
const closeUpdateTestimonialsModal = () => {
  isUpdateTestimonialsModalOpen.value = false;
  resetTestimonials();
};

// Employment Preferences Data
const employmentPreferences = ref({
  jobTypes: [],
  salaryExpectations: {
    min: '',
    max: '',
    currency: 'PESO',
    frequency: 'per year'
  },
  preferredLocations: [],
  workEnvironment: [],
  availability: [],
  additionalNotes: ''
});

// Add preferred location handler
const addPreferredLocation = (location) => {
  if (location && !employmentPreferences.value.preferredLocations.includes(location)) {
    employmentPreferences.value.preferredLocations.push(location);
  }
};

// Career Goals Data
const careerGoals = ref({
  shortTermGoals: '',
  longTermGoals: '',
  industriesOfInterest: [],
  careerPath: ''
});

// Resume Data
const resume = ref({
  file: null,
  fileName: ''
});

// Upload resume handler
const uploadResume = (event) => {
  const file = event.target.files[0];
  if (file) {
    resume.value.file = file;
    resume.value.fileName = file.name;
  }
};

// Remove resume handler
const removeResume = () => {
  resume.value.file = null;
  resume.value.fileName = '';
};
</script>

<template>
  <Graduate>
    <div class="bg-gray-100 min-h-screen p-6">
      <div class="container mx-auto">
        <h1 class="text-3xl font-bold mb-4">Profile Settings</h1>
        <p class="text-gray-600 mb-6">Manage your personal information and account settings</p>
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex border-b border-gray-200 mb-6">
            <button class="py-2 px-4" :class="{'text-purple-600 border-b-2 border-purple-600': activeSection === 'general'}" @click="setActiveSection('general')">
              General
            </button>

            <button class="py-2 px-4" :class="{'text-purple-600 border-b-2 border-purple-600': activeSection === 'security'}" @click="setActiveSection('security')">
              Security
            </button>

            <button class="py-2 px-4" :class="{'text-purple-600 border-b-2 border-purple-600': activeSection === 'education'}"@click="setActiveSection('education')">
              Education
            </button> 

            <button class="py-2 px-4" :class="{'text-purple-600 border-b-2 border-purple-600': activeSection === 'skills'}"@click="setActiveSection('skills')">
              Skills
            </button>

            <button class="py-2 px-4" :class="{'text-purple-600 border-b-2 border-purple-600': activeSection === 'experience'}" @click="setActiveSection('experience')">
              Experience
            </button>

            <button  class="py-2 px-4" :class="{'text-purple-600 border-b-2 border-purple-600': activeSection === 'projects'}" @click="setActiveSection('projects')">
              Projects
            </button>

            <button class="py-2 px-4" :class="{'text-purple-600 border-b-2 border-purple-600': activeSection === 'certifications'}" @click="setActiveSection('certifications')">
              Certifications
            </button>

            <button class="py-2 px-4" :class="{'text-purple-600 border-b-2 border-purple-600': activeSection === 'achievements'}" @click="setActiveSection('achievements')">
              Achievements
            </button>

            <button class="py-2 px-4" :class="{'text-purple-600 border-b-2 border-purple-600': activeSection === 'testimonials'}" @click="setActiveSection('testimonials')">
              Testimonials
            </button>

            <button class="py-2 px-4" :class="{'text-purple-600 border-b-2 border-purple-600': activeSection === 'employment'}" @click="setActiveSection('employment')">
              Employment
            </button>

            <button class="py-2 px-4" :class="{'text-purple-600 border-b-2 border-purple-600': activeSection === 'career-goals'}" @click="setActiveSection('career-goals')">
              Career Goals
            </button>

            <button class="py-2 px-4" :class="{'text-purple-600 border-b-2 border-purple-600': activeSection === 'resume'}"@click="setActiveSection('resume')">
              Resume
            </button>   
          </div>

          <!-- General Section -->
          <div v-if="activeSection === 'general'" class="flex flex-col lg:flex-row">
            <div class="w-full lg:w-1/3 mb-6 lg:mb-0">
              <h2 class="text-xl font-semibold mb-4">Profile Picture</h2>
              <p class="text-gray-600 mb-4">Update your profile picture</p>
              <div class="flex flex-col items-center">
                <img
                  id="profile-picture"
                  :src="profile.graduate_picture_url"
                  alt="Profile picture"
                  class="rounded-full mb-4 w-32 h-32 object-cover"
                />
                <input type="file" id="file-input" class="hidden" accept="image/*" @change="onFileChange" />
                <label for="file-input" class="text-purple-600 cursor-pointer">Choose an image</label>
              </div>
            </div>

            <div class="w-full lg:w-2/3">
              <h2 class="text-xl font-semibold mb-4">Profile Information</h2>
              <p class="text-gray-600 mb-4">Update your personal information</p>
              <form @submit.prevent="saveProfile">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                  <div class="relative">
                    <label for="full-name" class="block text-gray-700 mb-1">Full Name</label>
                    <i class="fas fa-user absolute left-3 top-10 text-gray-400"></i>
                    <input 
                      type="text" 
                      id="full-name"
                      class="w-full border border-gray-300 rounded-md p-2 pl-10 outline-none focus:ring-2 focus:ring-purple-600"
                      v-model="profile.fullName" 
                      placeholder="Enter your full name" 
                    />
                  </div>

                  <div class="relative">
                    <label for="professional-title" class="block text-gray-700 mb-1">Professional Title</label>
                    <i class="fas fa-briefcase absolute left-3 top-10 text-gray-400"></i>
                    <input
                      type="text"
                      id="professional-title"
                      class="w-full border border-gray-300 rounded-md p-2 pl-10 outline-none focus:ring-2 focus:ring-purple-600"
                      v-model="profile.graduate_professional_title"
                      placeholder="Enter your professional title"
                    />
                  </div>

                  <div class="relative">
                    <label for="email-address" class="block text-gray-700 mb-1">Email Address</label>
                    <i class="fas fa-envelope absolute left-3 top-10 text-gray-400"></i>
                    <input 
                      type="email" 
                      id="email-address"
                      class="w-full border border-gray-300 rounded-md p-2 pl-10 outline-none focus:ring-2 focus:ring-purple-600"
                      v-model="profile.graduate_email" 
                      placeholder="Enter your email address"
                    />
                  </div>

                  <div class="relative">
                    <label for="phone" class="block text-gray-700 mb-1">Phone Number</label>
                    <i class="fas fa-phone absolute left-3 top-10 text-gray-400"></i>
                    <input 
                      type="text" 
                      id="phone"
                      class="w-full border border-gray-300 rounded-md p-2 pl-10 outline-none focus:ring-2 focus:ring-purple-600"
                      v-model="profile.graduate_phone"
                      placeholder="Enter your phone number"
                    />
                  </div>

                  <div class="relative">
                    <label for="location" class="block text-gray-700 mb-1">Location</label>
                    <i class="fas fa-map-marker-alt absolute left-3 top-10 text-gray-400"></i>
                    <input 
                      type="text" 
                      id="location"
                      class="w-full border border-gray-300 rounded-md p-2 pl-10 outline-none focus:ring-2 focus:ring-purple-600"
                      v-model="profile.graduate_location"
                      placeholder="Enter your location"
                    />
                  </div>

                  <div class="relative">
                    <label for="birthdate" class="block text-gray-700 mb-1">Birthdate</label>
                    <i class="fas fa-calendar-alt absolute left-3 top-10 text-gray-400"></i>
                    <datepicker 
                      v-model="profile.graduate_birthdate"
                      :config="datepickerConfig" 
                      class="w-full border border-gray-300 rounded-md p-2 pl-10 outline-none focus:ring-2 focus:ring-purple-600"
                      placeholder="Select your birthdate"
                    />
                  </div>

                  <div class="relative">
                    <label for="gender" class="block text-gray-700 mb-1">Gender</label>
                    <i class="fas fa-venus-mars absolute left-3 top-10 text-gray-400"></i>
                    <select id="gender" class="w-full border border-gray-300 rounded-md p-2 pl-10 outline-none focus:ring-2 focus:ring-purple-600" v-model="profile.graduate_gender"> // Updated field name
                      <option value="" disabled selected>Select gender</option>
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                      <option value="Other">Other</option>
                      <option value="Prefer not to say">Prefer not to say</option>
                    </select>
                  </div>

                  <div class="relative">
                    <label for="ethnicity" class="block text-gray-700 mb-1">Ethnicity</label>
                    <i class="fas fa-user-friends absolute left-3 top-10 text-gray-400"></i>
                    <input
                      type="text"
                      id="ethnicity"
                      class="w-full border border-gray-300 rounded-md p-2 pl-10 outline-none focus:ring-2 focus:ring-purple-600"
                      v-model="profile.graduate_ethnicity" 
                      placeholder="Enter your ethnicity"
                    />
                  </div>
                </div>

                <div class="mb-4 relative">
                  <label for="address" class="block text-gray-700 mb-1">Address</label>
                  <i class="fas fa-home absolute left-3 top-10 text-gray-400"></i>
                  <input
                    type="text"
                    id="address"
                    class="w-full border border-gray-300 rounded-md p-2 pl-10 outline-none focus:ring-2 focus:ring-purple-600"
                    v-model="profile.graduate_address"
                    placeholder="Enter your address"
                  />
                </div>

                <div class="mb-4 relative">
                  <label for="about-me" class="block text-gray-700 mb-1">About Me</label>
                  <i class ="fas fa-info-circle absolute left-3 top-10 text-gray-400"></i>
                  <textarea
                    id="about-me"
                    class="w-full border border-gray-300 rounded-md p-2 pl-10 outline-none focus:ring-2 focus:ring-purple-600"
                    rows="4"
                    v-model="profile.graduate_about_me"
                    placeholder="Tell us about yourself"
                  ></textarea>
                </div>

                <button class="bg-purple-600 text-white py-2 px-4 rounded-md">
                  <i class="fas fa-save mr-2"></i> Save Changes
                </button>
              </form>
            </div>
          </div>

          <!-- Security Section -->
          <div v-if="activeSection === 'security'" class="flex flex-col lg:flex-row">
            <div class="w-full lg:w-1/1 mb-6 lg:mb-0">
              <h1 class="text-xl font-semibold mb-4">Change Password</h1>
              <p class="text-gray-600 mb-4">Update your password to maintain account security</p>
              <form @submit.prevent="handleSubmit">
                <div class="mb-4">
                  <label class="block text-gray-700 mb-2" for="current-password">Current Password</label>
                  <input
                    type="password"
                    id="current-password"
                    v-model="currentPassword"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                    placeholder="Current Password"
                  />
                </div>
                <div class="mb-4">
                  <label class="block text-gray-700 mb-2" for="new-password">New Password</label>
                  <input
                    type="password"
                    id="new-password"
                    v-model="newPassword"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                    placeholder="New Password"
                  />
                </div>
                <div class="mb-6">
                  <label class="block text-gray-700 mb-2" for="confirm-password">Confirm New Password</label>
                  <input
                    type="password"
                    id="confirm-password"
                    v-model="confirmPassword"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                    placeholder="Confirm New Password"
                  />
                </div>
                <button
                  type="submit"
                  class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-600">
                  <i class="fas fa-key mr-2"></i> Change Password
                </button>
              </form>
            </div>
          </div>

          <!-- Education Section -->
          <div v-if="activeSection === 'education'" class="flex flex-col lg:flex-row">
            <div class="w-full lg:w-1/1 mb-6 lg:mb-0">
              <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-semibold mb-4">Education</h1>
                <button class="bg-purple-600 text-white px-4 py-2 rounded flex items-center" @click="isAddEducationModalOpen = true">
                  <i class="fas fa-plus mr-2"></i> 
                  Add Education
                </button>
              </div>
              <p class="text-gray-600 mb-6">Manage your educational background</p>
              <div v-if="educationEntries.length > 0" class="bg-white p-4 rounded-lg shadow mb-4 relative">
                <div v-for="entry in educationEntries" :key="entry.id" class="flex justify-between items-center">
                  <div>
                    <h2 class="text-xl font-bold">{{ entry.graduate_education_institution_id }}</h2>
                    <p class="text-gray-600">{{ entry.graduate_education_program }} in {{ entry.graduate_education_field_of_study }}</p>
                    <div class="flex items-center text-gray-600 mt-2">
                      <i class="far fa-calendar-alt mr-2"></i>
                      <span>{{ entry.graduate_education_start_date }} - {{ entry.graduate_education_end_date }}</span>
                    </div>
                    <p class="mt-2">{{ entry.graduate_education_description }}</p>
                  </div>
                  <div class="absolute top-2 right-2 flex space-x-2">
                    <button class="text-gray-600" @click="openUpdateEducationModal(entry)">
                      <i class="fas fa-pen"></i>
                    </button>
                    <button class="text-red-600" @click="removeEducation(entry.id)">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </div>
              </div>

              <!-- If no education entries exist -->
              <div v-else class="bg-white p-4 rounded-lg shadow mb-4">
                <p class="text-gray-600">No education entries added yet.</p>
              </div>
            </div>

            <!-- Add Education Modal -->
            <div v-if="isAddEducationModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
              <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                  <h2 class="text-xl font-semibold">Add Education</h2>
                  <button class="text-gray-500 hover:text-gray-700" @click="closeAddEducationModal">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
                <p class="text-gray-600 mb-4">Add details about your educational background</p>
                <div class="max-h-96 overflow-y-auto">
                  <form @submit.prevent="addEducation">
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Institution</label>
                      <input type="text" v-model="education.graduate_education_institution_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" placeholder="e.g. Harvard University" required>
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Degree</label>
                      <input type="text" v-model="education.graduate_education_program" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" placeholder="e.g. Bachelor of Science" required>
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Field of Study</label>
                      <input type="text" v-model="education.graduate_education_field_of_study" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus :ring-2 focus:ring-purple-600" placeholder="e.g. Computer Science" required>
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Start Date</label>
                      <Datepicker
                        v-model="education.graduate_education_start_date"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                        placeholder="Select start date" required />
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">End Date</label>
                      <Datepicker
                        v-model="education.graduate_education_end_date"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                        placeholder="Select end date" required />
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Description</label>
                      <textarea v-model="education.graduate_education_description" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" rows="3" placeholder="Describe your experience..."></textarea>
                    </div>
                    <div class="flex justify-end">
                      <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">Add</button>
                    </div>
                  </form>              
                </div>
              </div>
            </div>

            <!-- Update Education Modal -->
            <div v-if="isUpdateEducationModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
              <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                  <h2 class="text-xl font-semibold">Update Education</h2>
                  <button class="text-gray-500 hover:text-gray-700" @click="closeUpdateEducationModal">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
                <p class="text-gray-600 mb-4">Update details about your educational background</p>
                <div class="max-h-96 overflow-y-auto">
                  <form @submit.prevent="updateEducation">
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Institution</label>
                      <input type="text" v-model="education.graduate_education_institution_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" placeholder="e.g. Harvard University">
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Degree</label>
                      <input type="text" v-model="education.graduate_education_program" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" placeholder="e.g. Bachelor of Science">
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Field of Study</label>
                      <input type="text" v-model="education.graduate_education_field_of_study" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" placeholder="e.g. Computer Science">
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Start Date</label>
                      <Datepicker
                        v-model="education.graduate_education_start_date"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                        placeholder="Select start date"
                      />
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">End Date</label>
                      <Datepicker
                        v-model="education.graduate_education_end_date"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                        placeholder="Select end date"
                      />
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Description</label>
                      <textarea v-model="education.graduate_education_description" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" rows="3" placeholder="Describe your experience..."></textarea>
                    </div>
                    <div class="flex justify-end">
                      <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">Update</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <!-- Skills Section -->
          <div v-if="activeSection === 'skills'" class="flex flex-col lg:flex-row">
            <div class="w-full lg:w-1/1 mb-6 lg:mb-0">
              <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-semibold">Skills</h1>
                <button class="bg-purple-600 text-white px-4 py-2 rounded flex items-center" @click="openAddSkillModal">
                  <i class="fas fa-plus mr-2"></i>
                  Add Skill
                </button>
              </div>
              <p class="text-gray-600 mb-6">Showcase your skills</p>
              <div v-if="Object.keys(groupedSkills).length > 0" class="bg-white p-4 rounded-lg shadow mb-4">
                <div v-for="(skillsGroup, type) in groupedSkills" :key="type" class="mb-4">
                  <h2 class="text-xl font-bold">{{ type }}</h2>
                  <div v-for="skill in skillsGroup" :key="skill.graduate_skills_name" class="mb-2">
                    <div class="flex justify-between items-center">
                      <span>{{ skill.graduate_skills_name }}</span>
                      <span class="bg-purple-600 text-white px-2 py-1 rounded-full text-sm">{{ skill.graduate_skills_proficiency }}%</span>
                      <div class="flex space-x-2">
                        <button class="text-gray-600" @click="editSkill(skill)">
                          <i class="fas fa-pen"></i> <!-- Edit icon -->
                        </button>
                        <button class="text-red-600" @click="removeSkill(skill)">
                          <i class="fas fa-trash"></i> <!-- Delete icon -->
                        </button>
                      </div>
                    </div>
                    <div class="relative h-2 bg-gray-200 rounded">
                      <div class="absolute top-0 left-0 h-2 bg-purple-600 rounded" :style="{ width: skill.graduate_skills_proficiency + '%' }"></div>
                    </div>
                    <p class="text-gray-600">Years of Experience: {{ skill.graduate_skills_years_experience }}</p>
                  </div>
                </div>
              </div>
              <div v-else class="bg-white p-4 rounded-lg shadow mb-4">
                <p class="text-gray-600">No skills added yet.</p>
              </div>
            </div>
          

            <!-- Add Skills Modal -->
            <div v-if="isAddSkillModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
              <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                  <h2 class="text-xl font-semibold">Add Skill</h2>
                  <button class="text-gray-500 hover:text-gray-700" @click="closeAddSkillModal">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
                <p class="text-gray-600 mb-4">Add a new skill to your profile</p>
                <form @submit.prevent="saveSkill">
                  <div class="mb-4">
                    <label for="skillName" class="block text-gray-700">Skill Name</label>
                    <input type="text" id="skillName" v-model="skillName" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                  </div>
                  <div class="mb-4">
                    <label for="proficiencyLevel" class="block text-gray-700">Proficiency Level (1-100)</label>
                    <input type="number" id="proficiencyLevel" v-model="proficiencyLevel" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                  </div>
                  <div class="mb-4">
                    <label for="skillType" class="block text-gray-700">Skill Type</label>
                    <select id="skillType" v-model="skillType" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                      <option value="" disabled>Select skill type</option>
                      <option value="Technical Skills">Technical Skills</option>
                      <option value="Soft Skills">Soft Skills</option>
                      <option value="Language Skills">Language Skills</option>
                      <option value="Tool/Platform">Tool/Platform</option>
                      <option value="Other Skills">Other Skills</option>
                    </select>
                  </div>
                  <div class="mb-4">
                    <label for="yearsExperience" class="block text-gray-700">Years of Experience</label>
                    <input type="number" id="yearsExperience" v-model="yearsExperience" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                  </div>
                  <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded-md flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i> Save Skill
                  </button>
                </form>
              </div>
            </div>

            <!-- Update Skills Modal -->
            <div v-if="isUpdateSkillModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
              <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                  <h2 class="text-xl font-semibold">Update Skill</h2>
                  <button class="text-gray-500 hover:text-gray-700" @click="closeUpdateSkillModal">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
                <p class="text-gray-600 mb-4">Update the skill details</p>
                <form @submit.prevent="updateSkill">
                  <div class="mb-4">
                    <label for="skillName" class="block text-gray-700">Skill Name</label>
                    <input type="text" id="skillName" v-model="skillName" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                  </div>
                  <div class="mb-4">
                    <label for="proficiencyLevel" class="block text-gray-700">Proficiency Level (1-100)</label>
                    <input type="number" id="proficiencyLevel" v-model="proficiencyLevel" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                  </div>
                  <div class="mb-4">
                    <label for="skillType" class="block text-gray-700">Skill Type</label>
                    <select id="skillType" v-model="skillType" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                      <option value="" disabled>Select skill type</option>
                      <option value="Technical">Technical Skills</option>
                      <option value="Soft">Soft Skills</option>
                      <option value="Language">Language Skills</option>
                      <option value="Tool/Platform">Tool/Platform</option>
                      <option value="Other">Other Skills</option>
                    </select>
                  </div>
                  <div class="mb-4">
                    <label for="yearsExperience" class="block text-gray-700">Years of Experience</label>
                    <input type="number" id="yearsExperience" v-model="yearsExperience" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                  </div>
                  <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded-md flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i> Update Skill
                  </button>
                </form>
              </div>
            </div>
          </div>

          <!-- Experience Section -->
          <div v-if="activeSection === 'experience'" class="flex flex-col lg:flex-row">
            <div class="w-full lg:w-1/1 mb-6 lg:mb-0">
              <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-semibold mb-4">Work Experience</h1>
                <button class="bg-purple-600 text-white px-4 py-2 rounded flex items-center" @click="isAddExperienceModalOpen = true">
                  <i class="fas fa-plus mr-2"></i> Add Experience
                </button>
              </div>

              <p class="text-gray-600 mb-6">Showcase your professional experience</p>
              <div v-if="experienceEntries.length > 0" class="bg-white p-4 rounded-lg shadow mb-4 relative">
                <div v-for="entry in experienceEntries" :key="entry.id" class="flex justify-between items-center">
                  <div>
                    <h2 class="text-xl font-bold">{{ entry.graduate_experience_title }}</h2>
                    <p class="text-gray-600">{{ entry.graduate_experience_company }}</p>
                    <div class="flex items-center text-gray-600 mt-2">
                      <i class="fas fa-calendar-alt mr-2"></i>
                      <span>{{ entry.graduate_experience_start_date }} - {{ entry.graduate_experience_end_date }}</span>
                      <i class="fas fa-map-marker-alt ml-4 mr-2"></i>
                      <span>{{ entry.graduate_experience_address }}</span>
                    </div>
                  </div>

                  <div class="absolute top-2 right-2 flex space-x-2">
                    <button class="text-gray-600" @click="openUpdateExperienceModal(entry, index)">
                      <i class="fas fa-pen"></i>
                    </button>

                    <button class="text-red-600" @click="removeExperience(entry.id)">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </div>
              </div>

              <div v-else class="bg-white p-4 rounded-lg shadow mb-4">
                <p class="text-gray-600">No experience entries added yet.</p>
              </div>
            </div>

            <!-- Add Experience Modal -->
            <div v-if="isAddExperienceModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
              <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                  <h2 class="text-xl font-semibold">Add Experience</h2>
                  <button class="text-gray-500 hover:text-gray-700" @click="closeAddExperienceModal">
                    <i class="fas fa-times"></i>
                  </button>
                </div>

                <p class="text-gray-600 mb-4">Add details about your experience background</p>
                <div class="max-h-96 overflow-y-auto">
                  <form @submit.prevent="addExperience">
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Title</label>
                      <input type="text" v-model="experience.graduate_experience_title" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" placeholder="e.g. Software Engineer" required>
                    </div>

                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Company</label>
                      <input type="text" v-model="experience.graduate_experience_company" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" placeholder="e.g. Tech Corp" required>
                    </div>

                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Start Date</label>
                      <Datepicker
                        v-model="experience.graduate_experience_start_date"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                        placeholder="Select start date" required />
                    </div>

                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">End Date</label>
                      <Datepicker
                        v-model="experience.graduate_experience_end_date"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                        placeholder="Select end date" required />
                    </div>

                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Work Location</label>
                      <input type="text" v-model="experience.graduate_experience_address" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" placeholder="e.g. New York, NY" required>
                    </div>

                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Key Achievements</label>
                      <textarea v-model="experience.graduate_experience_achievements" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" rows="3" placeholder="Describe your achievements..."></textarea>
                    </div>

                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Skills & Technology</label>
                      <textarea v-model="experience.graduate_experience_skills_tech" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" rows="3" placeholder="Describe your skills..."></textarea>
                    </div>

                    <div class="flex justify-end">
                      <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">Add</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>


            <!-- Update Experience Modal -->
            <div v-if="isUpdateExperienceModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
              <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                  <h2 class="text-xl font-semibold">Update Experience</h2>
                  <button class="text-gray-500 hover:text-gray-700" @click="closeUpdateExperienceModal">
                    <i class="fas fa-times"></i>
                  </button>
                </div>

                <p class="text-gray-600 mb-4">Update details about your experience background</p>
                <div class="max-h-96 overflow-y-auto">
                  <form @submit.prevent="updateExperience">
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Title</label>
                      <input type="text" v-model="experience.graduate_experience_title" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" placeholder="e.g. Software Engineer" required>
                    </div>

                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Company</label>
                      <input type="text" v-model="experience.graduate_experience_company" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:极ring-2 focus:ring-purple-600" placeholder="e.g. Tech Corp" required>
                    </div>

                    <div class="mb-4">
                      <label class="极block text-gray-700 font-medium mb-2">Start Date</label>
                      <Datepicker
                        v-model="experience.graduate_experience_start_date"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                        placeholder="Select start date" required />
                    </div>

                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">End Date</label>
                      <Datepicker
                        v-model="experience.graduate_experience_end_date"
                        class="w-full px极-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                        placeholder="Select end date" required />
                    </div>

                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Work Location</label>
                      <input type="text" v-model="experience.graduate_experience_address" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" placeholder="e.g. New York, NY" required>
                    </div>

                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Key Achievements</label>
                      <textarea v-model="experience.graduate_experience_achievements" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" rows="3" placeholder="Describe your achievements..."></textarea>
                    </div>

                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Skills & Technology</label>
                      <textarea v-model="experience.graduate_experience_skills_tech" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" rows极="3" placeholder="Describe your skills..."></textarea>
                    </div>

                    <div class="flex justify-end">
                      <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">Update</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <!-- Project Section -->

          <!-- Certifications Section -->
          <div v-if="activeSection === 'certifications'" class="flex flex-col lg:flex-row">
            <div class="w-full lg:w-1/1 mb-6 lg极:mb-0">
              <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-semibold mb-4">Certifications</h1>
                <button class="bg-purple-600 text-white px-4 py-2 rounded flex items-center hover:bg-purple-700" @click="isAddCertificationModalOpen = true">
                  <i class="fas fa-plus mr-2"></i>
                  Add Certification
                </button>
              </div>
              <p class="text-gray-600 mb-6">Manage your professional certifications</p>

              <!-- Certifications List -->
              <div v-if="certificationEntries.length > 0" class="bg-white p-4 rounded-lg shadow mb-4 relative">
                <div v-for="entry in certificationEntries" :key="entry.id" class="flex justify-between items-center">
                  <div>
                    <h2 class="text-xl font-bold">{{ entry.graduate_certification_name }}</h2>
                    <p class="text-gray-600">{{ entry.graduate_certification_issuer }}</p>
                    <div class="flex items-center text-gray-600 mt-2">
                      <i class="far fa-calendar-alt mr-2"></i>
                      <span>{{ entry.graduate_certification_issue_date }} - {{ entry.graduate_certification_expiry_date }}</span>
                    </div>
                    <p class="mt-2">Credential ID: {{ entry.graduate_certification_credential_id }}</p>
                    <p class="mt-2">
                      URL: <a :href="entry.graduate_certification_credential_url" class="text-blue-600 hover:underline" target="_blank">{{ entry.graduate_certification_credential_url }}</a>
                    </p>
                  </div>
                  <div class="absolute top-2 right-2 flex space-x-2">
                    <button class="text-gray-600 hover:text-purple-600" @click="openUpdateCertificationModal(entry)">
                      <i class="fas fa-pen"></i>
                    </button>
                    <button class="text-red-600 hover:text-red-800" @click="removeCertification(entry.id)">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </div>
              </div>

              <!-- If no certifications exist -->
              <div v-else class="bg-white p-4 rounded-lg shadow mb-4">
                <p class="text-gray-600">No certifications added yet.</p>
              </div>
            </div>
          
            <!-- Add Certification Modal -->
            <div v-if="isAddCertificationModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
              <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                  <h2 class="text-xl font-semibold">Add Certification</h2>
                  <button class="text-gray-500 hover:text-gray-700" @click="closeAddCertificationModal">
                    <i class="fas fa-times"></i>
                  </button>
                </div>

                <form @submit.prevent="addCertification">
                  <div class="mb-4">
                    <label for="certification-name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="certification-name" v-model="certification.graduate_certification_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm" required>
                  </div>

                  <div class="mb-4">
                    <label for="certification-issuer" class="block text-sm font-medium text-gray-700">Issuer</label>
                    <input type="text" id="certification-issuer" v-model="certification.graduate_certification_issuer" class="mt极-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm" required>
                  </div>

                  <div class="mb-4">
                    <label for="certification-issue-date" class="block text-sm font-medium text-gray-700">Issue Date</label>
                    <Datepicker v-model="certification.graduate_certification_issue_date" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" placeholder="Select issue date" required />
                  </div>

                  <div class="mb-4">
                    <label for="certification-expiry-date" class="block text-sm font-medium text-gray-700">Expiry Date</label>
                    <Datepicker
                      v-model="certification.graduate_certification_expiry_date"
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                      placeholder="Select expiry date"
                      :disabled="noExpiryDate"
                    />

                    <div class="mt-2">
                      <input
                        type="checkbox"
                        id="no-expiry-date"
                        v-model="noExpiryDate"
                        class="mr-2"
                      />
                    <label for="no-expiry-date" class="text-sm text-gray-700">No expiry date</label>
                    </div>
                  </div>

                  <div class="mb-4">
                    <label for="certification-credential-id" class="block text-sm font-medium text-gray-700">Credential ID</label>
                    <input type="text" id="certification-credential-id" v-model="certification.graduate_certification_credential_id" class="mt-1 block w-full px-3 py极-2 border border-gray极-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                  </div>

                  <div class="mb-4">
                    <label for="certification-credential-url" class="block text-sm font-medium text-gray-700">Credential URL</label>
                    <input type="text" id="certification-credential-url" v-model="certification.graduate_certification_credential_url" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                  </div>

                  <div class="flex justify-end">
                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus极:ring-purple-500">Save</button>
                  </div>
                </form>
              </div>
            </div>
          

            <!-- Update Certification Modal -->
            <div v-if="isUpdateCertificationModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
              <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                  <h2 class="text-xl font-semibold">Update Certification</h2>
                  <button class="text-gray-500 hover:text-gray-700" @click="closeUpdateCertificationModal">
                    <i class="fas fa-times"></i>
                  </button>
                </div>

                <form @submit.prevent="updateCertification">
                  <div class="mb-4">
                    <label for="certification-name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="certification-name" v-model="certification.graduate_certification_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm" required>
                  </div>

                  <div class="mb-4">
                    <label for="certification-issuer" class="block text-sm font-medium text-gray-700">Issuer</label>
                    <input type="text" id="certification-issuer" v-model="certification.graduate_certification_issuer" class="mt极-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm" required>
                  </div>

                  <div class="mb-4">
                    <label for="certification-issue-date" class="block text-sm font-medium text-gray-700">Issue Date</label>
                    <Datepicker v-model="certification.graduate_certification_issue_date" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" placeholder="Select issue date" required />
                  </div>

                  <div class="mb-4">
                    <label for="certification-expiry-date" class="block text-sm font-medium text-gray-700">Expiry Date</label>
                    <Datepicker
                      v-model="certification.graduate_certification_expiry_date"
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                      placeholder="Select expiry date"
                      :disabled="noExpiryDate"
                     />
                    <div class="mt-2">
                      <input
                        type="checkbox"
                        id="no-expiry-date"
                        v-model="noExpiryDate"
                        class="mr-2"
                      />
                    <label for="no-expiry-date" class="text-sm text-gray-700">No expiry date</label>
                    </div>
                  </div>

                  <div class="mb-4">
                    <label for="certification-credential-id" class="block text-sm font-medium text-gray-700">Credential ID</label>
                    <input type="text" id="certification-credential-id" v-model="certification.graduate_certification_credential_id" class="mt-1 block w-full px-3 py极-2 border border-gray极-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                  </div>

                  <div class="mb-4">
                    <label for="certification-credential-url" class="block text-sm font-medium text-gray-700">Credential URL</label>
                    <input type="text" id="certification-credential-url" v-model="certification.graduate_certification_credential_url" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                  </div>

                  <div class="flex justify-end">
                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus极:ring-purple-500">Update</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
  
            <!-- Achievement Section -->
            <div v-if="activeSection === 'achievements'" class="flex -col lg:flex-row">
              <div class="w-full lg:w-1/1 mb-6 lg:mb-0">
                <div class="flex justify-between items-center mb-4">
                  <h1 class="text-xl font-semibold mb-4">Achievements</h1>
                  <button class="bg-purple-600 text-white px-4 py-2 rounded flex items-center" @click="isAddAchievementModalOpen = true">
                    <i class="fas fa-plus mr-2"></i> 
                    Add Achievement
                  </button>
                </div>
                <p class="text-gray-600 mb-6">Showcase your awards and recognition</p>
                <div v-if="achievementEntries.length > 0" class="bg-white p-4 rounded-lg shadow mb-4 relative">
                  <div v-for="entry in achievementEntries" :key="entry.id" class="flex justify-between items-center">
                    <div>
                      <h2 class="text-xl font-bold">{{ entry.graduate_achievement_title }}</h2>
                      <p class="text-gray-600">{{ entry.graduate_achievement_issuer }}</p> 
                      <div class="flex items-center text-gray-600 mt-2">
                        <i class="far fa-calendar-alt mr-2"></i>
                        <span>{{ entry.graduate_achievement_date }}</span>
                      </div>
                      <p class="mt-2">{{ entry.graduate_achievement_description }}</p>
                      <p class="mt-2">URL: {{ entry.graduate_achievement_url }} </p>
                      <p class="mt-2">Type:: {{ entry.graduate_achievement_credential_id }} </p>
                    </div>
                    <div class="absolute top-2 right-2 flex space-x-2">
                      <button class="text-gray-600" @click="openUpdateachievementModal(entry)">
                        <i class="fas fa-pen"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <!-- If no education entries exist -->
                <div v-else class="bg-white p-4 rounded-lg shadow mb-4">
                  <p class="text-gray-600">No achievement entries added yet.</p>
                </div>
              </div>
            

              <!-- Add Achievement Modal -->
              <div v-if="isAddAchievementModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                  <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Add Achievement</h2>
                    <button class="text-gray-500 hover:text-gray-700" @click="closeAddAchievementModal">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                    <p class="text-gray-600 mb-4">Add details about awards, recognition, or other achievements</p>
                    <div class="max-h-96 overflow-y-auto">
                      <form @submit.prevent="addAchievement">
                        <div class="mb-4">
                          <label class="block text-gray-700 font-medium mb-2">Title</label>
                          <input type="text" v-model="achievement.graduate_achievement_title" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" placeholder="e.g. Harvard University" required>
                        </div>
                        <div class="mb-4">
                          <label class="block text-gray-700 font-medium mb-2">Issuer</label>
                          <input type="text" v-model="achievement.graduate_achievement_issuer" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" placeholder="e.g. Bachelor of Science" required>
                        </div>
                        
                        <div class="mb-4">
                          <label class="block text-gray-700 font-medium mb-2">Date</label>
                          <Datepicker
                            v-model="achievement.graduate_achievement_date"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                            placeholder="Select start date" required />
                        </div>
                        
                        <div class="mb-4">
                          <label class="block text-gray-700 font-medium mb-2">Description</label>
                          <textarea v-model="achievement.graduate_achievement_description" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" rows="3" placeholder="Describe your experience..."></textarea>
                        </div>

                        <div class="mb-4">
                          <label for="achievement-url" class="block text-sm font-medium text-gray-700">Credential URL</label>
                          <input type="text" id="certification-credential-url" v-model="achievement.graduate_achievement_url" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                        </div>

                        <div class="mb-4">
                          <label for="achievementType" class="block text-gray-700">AchievementType</label>
                          <select id="achievementType" v-model="achievementType" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            <option value="" enabled>Select achievement type</option>
                            <option  ption value="Award">Award</option>
                            <option value="Recognition">Recognition</option>
                            <option value="Publication">Publication</option>
                            <option value="Patent">Patent</option>
                            <option value="Other">Other</option>
                          </select>
                        </div>
                        <div class="flex justify-end">
                          <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">Add</button>
                        </div>
                      </form>              
                    </div>
                  </div>
                </div>

            <!-- Update Achievement Modal -->
            <div v-if="isUpdateAchievementModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
              <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                  <h2 class="text-xl font-semibold">Update Achievement</h2>
                  <button class="text-gray-500 hover:text-gray-700" @click="closeUpdateAchievementModal">
                    <i class="fas fa-times"></i>
                  </button>
                  </div>
                  <p class="text-gray-600 mb-4">Add details about awards, recognition, or other achievements</p>
                  <div class="max-h-96 overflow-y-auto">
                    <form @submit.prevent="updateAchievement">
                      <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Title</label>
                        <input type="text" v-model="achievement.graduate_achievement_title" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" placeholder="e.g. Harvard University" required>
                      </div>
                      <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Issuer</label>
                        <input type="text" v-model="achievementn.graduate_achievement_issuer" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" placeholder="e.g. Bachelor of Science" required>
                      </div>
                          
                      <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Date</label>
                        <Datepicker
                          v-model="achievement.graduate_achievement_date"
                          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                          placeholder="Select start date" required />
                      </div>
                          
                      <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Description</label>
                        <textarea v-model="achievement.graduate_achievement_description" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" rows="3" placeholder="Describe your experience..."></textarea>
                      </div>

                      <div class="mb-4">
                        <label for="achievement-url" class="block text-sm font-medium text-gray-700">Credential URL</label>
                        <input type="text" id="certification-credential-url" v-model="achievement.graduate_achievement_url" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                      </div>

                      <div class="mb-4">
                        <label for="achievementType" class="block text-gray-700">AchievementType</label>
                        <select id="achievementType" v-model="achievementType" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                          <option value="" enabled>Select achievement type</option>
                          <option value="Award">Award</option>
                          <option value="Recognition">Recognition</option>
                          <option value="Publication">Publication</option>
                          <option value="Patent">Patent</option>
                          <option value="Other">Other</option>
                        </select>
                      </div>
                      <div class="flex justify-end">
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">Add</button>
                      </div>
                    </form>              
                  </div>
                </div>
              </div>
            </div>
            

          <!-- Testimonials Section-->
          <div v-if="activeSection === 'testimonials'" class="flex flex-col lg:flex-row">
            <div class="w-full lg:w-1/1 mb-6 lg:mb-0">
              <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-semibold mb-4">Testimonials</h1>
                <button class="bg-purple-600 text-white px-4 py-2 rounded flex items-center" @click="isAddEducationModalOpen = true">
                    <i class="fas fa-plus mr-2"></i> 
                  Add Testimonials
                </button>
              </div>
              <p class="text-gray-600 mb-6">Recommendations from colleagues and clients</p>
              <div v-if="testimonialsEntries.length > 0" class="bg-white p-4 rounded-lg shadow mb-4 relative">
                <div v-for="entry in testimonialsEntries" :key="entry.id" class="flex justify-between items-center">
                  <div>
                    <h2 class="text-xl font-bold">{{ entry.graduate_testimonials_name }}</h2>
                    <p class="text-gray-600">{{ entry.graduate_testimonials_role_title }}</p>
                    <p class="text-gray-600">{{ entry.graduate_testimonials_relationship }}</p>
                    <p class="mt-2">{{ entry.graduate_testimonials_testimonial }}</p>
                  </div>
                  <div class="absolute top-2 right-2 flex space-x-2">
                    <button class="text-gray-600" @click="openUpdateTestimonialsModal(entry)">
                      <i class="fas fa-pen"></i>
                    </button>
                    <button class="text-red-600" @click="removeTestimonials(entry.id)">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </div>
              </div>

              <!-- If no testimonials entries exist -->
              <div v-else class="bg-white p-4 rounded-lg shadow mb-4">
                <p class="text-gray-600">No testimonial entries added yet.</p>
              </div>
            </div>

            <!-- Add Testimonials Modal -->
            <div v-if="isAddTestimonialsModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
              <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                  <h2 class="text-xl font-semibold">Add Testimonials</h2>
                  <button class="text-gray-500 hover:text-gray-700" @click="closeAddTestimonialsModal">
                    <i class="fas fa-times"></i>
                  </button>
                  </div>
                  <p class="text-gray-600 mb-4">Add a recommendation from a colleague or client</p>
                  <div class="max-h-96 overflow-y-auto">
                    <form @submit.prevent="addTestimonials">
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Name</label>
                      <input type="text" v-model="testimonials.graduate_testimonials_name" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" placeholder="e.g. Harvard University" required>
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Role/Title</label>
                      <input type="text" v-model="testimonials.graduate_testimonials_role_title" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" placeholder="e.g. Bachelor of Science" required>
                    </div>
                    <div class="mb-4">
                      <label for="relationshipType" class="block text-gray-700">Relationship</label>
                      <select id="relationshipType" v-model="relationshipType" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                        <option value="" enabled>Select relationship type</option>
                        <option value="Manager/Supervisor">Manager/Supervisor</option>
                        <option value="Colleage">Colleage</option>
                        <option value="Client">Client</option>
                        <option value="Direct Report">Direct Report</option>
                        <option value="Mentor">Mentor</option>
                        <option value="Other">Other</option>
                      </select>
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Testimonial</label>
                      <textarea v-model="testimonials.graduate_testimonials_testimonial" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" rows="3" placeholder="Describe your experience..."></textarea>
                    </div>
                    <div class="flex justify-end">
                      <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">Add</button>
                    </div>
                  </form>              
                </div>
              </div>
            </div>

            <!-- Update Testimonials Modal -->
            <div v-if="isUpdateTestimonialsModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
              <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                  <h2 class="text-xl font-semibold">Update Testimonials</h2>
                  <button class="text-gray-500 hover:text-gray-700" @click="closeUpdateTestimonialsModal">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
                <p class="text-gray-600 mb-4">Add a recommendation from a colleague or client</p>
                <div class="max-h-96 overflow-y-auto">
                  <form @submit.prevent="updateTestimonials">
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Name</label>
                      <input type="text" v-model="testimonials.graduate_testimonials_name" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" placeholder="e.g. Harvard University" required>
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Role/Title</label>
                      <input type="text" v-model="testimonials.graduate_testimonials_role_title" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" placeholder="e.g. Bachelor of Science" required>
                    </div>
                    <div class="mb-4">
                      <label for="relationshipType" class="block text-gray-700">Relationship</label>
                      <select id="relationshipType" v-model="relationshipType" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                        <option value="" enabled>Select relationship type</option>
                        <option value="Manager/Supervisor">Manager/Supervisor</option>
                        <option value="Colleage">Colleage</option>
                        <option value="Client">Client</option>
                        <option value="Direct Report">Direct Report</option>
                        <option value="Mentor">Mentor</option>
                        <option value="Other">Other</option>
                      </select>
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Testimonial</label>
                      <textarea v-model="testimonials.graduate_testimonials_testimonial" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" rows="3" placeholder="Describe your experience..."></textarea>
                    </div>
                    <div class="flex justify-end">
                      <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">Update</button>
                    </div>
                  </form>              
                </div>
              </div>
            </div>
          </div>
        

          <!-- Employment Preferences Section -->
          <div v-if="activeSection === 'employment'" class="flex flex-col lg:flex-row">
            <div class="w-full lg:w-1/1 mb-6 lg:mb-0">
              <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-semibold mb-4">Employment Preferences</h1>
              </div>
              <p class="text-gray-600 mb-6">Set your job preferences and requirements</p>
              <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Job Types</h2>
                <div class="flex flex-wrap gap-2">
                  <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded-full hover:bg-gray-300">Full-time</button>
                  <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded-full hover:bg-gray-300">Part-time</button>
                  <button class="bg-gray-200 text-gray极-700 px-4 py-2 rounded-full hover:bg-gray-300">Contract</button>
                  <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded-full hover:bg-gray-300">Freelance</button>
                  <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded-full hover:bg-gray-300">Internship</button>
                </div>
              </div>
              <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Salary Expectations</h2>
                <div class="flex items-center gap-2">
                  <input type="text" class="border border-gray-300 rounded px-4 py-2 w-24 focus:ring-2 focus:ring-purple-600" placeholder="Min">
                  <span class="text-gray-600">-</span>
                  <input type="text" class="border border-gray-300 rounded px-4 py-2 w-24 focus:ring-2 focus:ring-purple-600" placeholder="Max">
                  <select class="border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-purple-600">
                    <option>PESO</option>
                    <option>USD</option>
                  </select>
                  <select class="border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-purple-600">
                    <option>per year</option>
                    <option>per month</option>
                    <option>per hour</option>
                  </select>
                </div>
              </div>
              <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Preferred Locations</h2>
                <div class="flex flex-wrap gap-2">
                  <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded-full hover:bg-gray-300">Remote</button>
                  <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded-full hover:bg-gray-300">Baluan</button>
                  <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded-full hover:bg-gray-300">Buayan</button>
                  <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded-full hover:bg-gray-300">Bula</button>
                  <button class="bg-purple-600 text-white px-4 py-2 rounded-full hover:bg-purple-700" @click="isAddLocationModalOpen = true">+ Add Location</button>
                </div>
              </div>
              <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Work Environment</h2>
                 <div class="flex flex-wrap gap-4">
                  <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox text-purple-600 focus:ring-purple-600" />
                    <span class="ml-2 text-gray-700">Remote</span>
                  </label>
                  <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox text-purple-600 focus:ring-purple-600" />
                    <span class="ml-2 text-gray-700">Hybrid</span>
                  </label>
                  <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox text-purple-600 focus:ring-purple-600" />
                    <span class="ml-2 text-gray-700">On-site</span>
                  </label>
                </div>
              </div>
              <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Availability</h2>
                <div class="flex flex-wrap gap-4">
                  <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox text-purple-600 focus:ring-purple-600" />
                    <span class="ml-2 text-gray-700">Immediately</span>
                  </label>
                  <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox text-purple-600 focus:ring-purple-600" />
                    <span class="ml-2 text-gray-700">2 weeks notice</span>
                  </label>
                  <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox text-purple-600 focus:ring-purple-600" />
                    <span class="ml-2 text-gray-700">1 month notice</span>
                  </label>
                </div>
              </div>
              <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Additional Notes</h2>
                <textarea class="border border-gray-300 rounded w-full px-4 py-2 focus:ring-2 focus:ring-purple-600" rows="4" placeholder="Any other preferences or requirements..."></textarea>
              </div>
              <div class="text-right">
                <button class="bg-purple-600 text-white px-6 py-2 rounded-full hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-600">
                  Save Preferences
                </button>
              </div>
            </div>
            <div v-if="isAddLocationModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
              <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                  <h2 class="text-xl font-semibold">Add Location</h2>
                    <button class="text-gray-500 hover:text-gray-700" @click="closeAddLocationModal">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                <p class="text-gray-600 mb-4">Add a preferred location for employment</p>
                <div class="max-h-96 overflow-y-auto">
                  <form @submit.prevent="addPreferredLocation(newLocation)">
                  <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Location</label>
                    <input type="text" v-model="newLocation" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" placeholder="e.g. New York, NY" required>
                  </div>
                  <div class="flex justify-end">
                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">Add</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

            <!-- Career Goals Section-->
            <div v-if="activeSection === 'career-goals'" class="flex flex-col lg:flex-row">
              <div class="w-full lg:w-1/1 mb-6 lg:mb-0">
                <div class="flex justify-between items-center mb-4">
                  <h1 class="text-xl font-semibold mb-4">Career Goals</h1>
                </div>
                <p class="text-gray-600 mb-6">Define your short and long-term career aspirations</p>
                <div class="mb-6">
                  <h2 class="text-xl font-semibold mb-2">Short-term Goals (1-2 years)</h2>
                  <textarea
                    class="w-full p-4 border border-gray-300 rounded-lg"
                    rows="3"
                    v-model="careerGoals.shortTermGoals">
                  </textarea>
                </div>
                <div class="mb-6">
                  <h2 class="text-xl font-semibold mb-2">Long-term Goals (3-5 years)</h2>
                  <textarea
                    class="w-full p-4 border border-gray-300 rounded-lg"
                    rows="3"
                    v-model="careerGoals.longTermGoals">
                  </textarea>
                </div>
                <div class="mb-6">
                  <h2 class="text-xl font-semibold mb-2">Industries of Interest</h2>
                  <div class="flex flex-wrap gap-2">
                    <button class="bg-purple-600 text-white px-4 py-2 rounded-full">+ Add Industry</button>
                  </div>
                </div>

                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-2">Career Path</h2>
                    <input 
                      type="text" 
                      class="w-full border border-gray-300 rounded-md p-2 outline-none focus:ring-2 focus:ring-purple-600"
                      v-model="careerGoals.careerPath" 
                      placeholder="Enter your career path" 
                    />
                  </div>

                  <button class="bg-purple-600 text-white px-6 py-3 rounded-lg flex items-center" @click="saveCareerGoals">
                    <i class="fas fa-save mr-2"></i> Save Goals
                  </button>
                </div>
              </div>
           

                <!-- Resume Section -->
                  <div v-if="activeSection === 'resume'" class="flex flex-col lg:flex-row">
                      <div class="w-full lg:w-1/3 mb-6 lg:mb-0">
                        <h2 class="text-xl font-semibold mb-4">Resume</h2>
                        <p class="text-gray极-600 mb-4">Upload and manage your resume</p>
                        <div class="bg-white p-4 rounded-lg shadow-md">
                          <div class="flex items-center justify-between border border-gray-300 rounded-lg p-4 mb-4">
                            <div class="flex items-center">
                              <i class="fas fa-file-alt text-gray-500 text-2xl mr-4"></i>
                              <span>No resume uploaded yet</span>
                            </div>
                          </div>
                          <button class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-600">
                            <i class="fas fa-upload mr-2"></i>
                            Upload New Resume
                          </button>
                        </div>
                      </div>
                    </div>
        
          </div>
          <teleport to="body">
          <div v-if="isProfileModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded shadow-lg">
              <h2 class="text-lg font-bold">Profile Information Updated</h2>
              <p>Your profile information has been successfully updated.</p>
              <button @click="closeProfileModal" class="mt-4 bg-purple-600 text-white px-4 py-2 rounded">Close</button>
            </div>
          </div>

          <div v-if="isPasswordModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded shadow-lg">
              <h2 class="text-lg font-bold">Password Updated</h2>
              <p>Your password has been successfully updated.</p>
              <button @click="closePasswordModal" class="mt-4 bg-purple-600 text-white px-4 py-2 rounded">Close</button>
            </div>
          </div>

          <div v-if="isEducationAddedModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded shadow-lg">
              <h2 class="text-lg font-bold">Education Added</h2>
              <p>Your education has been successfully added.</p>
              <button @click="closeEducationAddedModal" class="mt-4 bg-purple-600 text-white px-4 py-2 rounded">Close</button>
            </div>
          </div>

          <div v-if="isEducationUpdatedModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded shadow-lg">
              <h2 class="text-lg font-bold">Education Updated</h2>
              <p>Your education details have been successfully updated.</p>
              <button @click="closeEducationUpdatedModal" class="mt-4 bg-purple-600 text-white px-4 py-2 rounded">Close</button>
            </div>
          </div>  

          <div v-if="isSkillsAddedModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded shadow-lg">
              <h2 class="text-lg font-bold">Skill Added</h2>
              <p>Your skills have been successfully added.</p>
              <button @click="closeSkillsAddedModal" class="mt-4 bg-purple-600 text-white px-4 py-2 rounded">Close</button>
            </div>
          </div>
        </teleport>
      </div>
    </div>
  </Graduate>
</template>