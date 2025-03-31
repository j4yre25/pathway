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


const passwordInput = ref(null);
const currentPasswordInput = ref(null);

// Props and form setup
const props = defineProps({
  user: {
    type: Object,
    required: true
  },
  aboutMe: {
    type: Object,
    default: () => ({})
  },
  portfolioItems: {
    type: Array,
    default: () => []
  },
  portfolio: {
    type: Object,
    default: () => ({})
  }
});

// Initialize form with portfolio data

const form = useForm({
  _method: 'PUT',
  graduate_first_name: props.user.graduate_first_name || '',
  graduate_middle_initial: props.user.graduate_middle_initial || '',
  graduate_last_name: props.user?.graduate_last_name || '',
  email: props.user?.email || '',
  professional_title: props.user?.graduate_professional_title || '',
  personal_summary: props.  aboutMe?.personal_summary || '',
  experience_level: props.aboutMe?.experience_level || '',
  skills: props.aboutMe?.skills || [],
  experience: props.aboutMe?.career_history || [],
  education: props.aboutMe?.education || [],
  portfolio: {
    workSamples: [],
    awards: [],
    documents: [],
    professionalDevelopment: [],
    volunteerWork: [],
    professionalMemberships: []
  },
  files: null,
  nextRole: {
    title: '',
    company: '',
    start_date: '',
    end_date: '',
    description: ''
  },
  nextEducation: {
    degree: '',
    institution: '',
    year: ''
  }
});

// Initialize portfolio items from props with reactive ref
const portfolioItems = ref(props.portfolioItems || []);

// Initialize settings form with user data
const settingsForm = useForm({
  currentEmail: props.user?.email || '',
  newEmail: '',
  confirmNewEmail: '',
  current_password: '',
  password: '',
  password_confirmation: '',
});

const updatePassword = () => {
    settingsForm.put(route('user-password.update'), {
        errorBag: 'updatePassword',
        preserveScroll: true,
        onSuccess: () => settingsForm.reset(),
        onError: () => {


          console.log('Form Data:', {
        current_password: settingsForm.current_password,
        password: settingsForm.password,
        password_confirmation: settingsForm.password_confirmation,
      });

            if (settingsForm.errors.password) {
              settingsForm.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }

            if (settingsForm.errors.current_password) {
              settingsForm.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};

// Initialize skills from aboutMe data
const skillsInput = ref('');
const skills = ref(props.aboutMe?.skills || []);

// Modal state
const modalType = ref('');
const modalTitles = {
  profile: 'Edit Profile',
  settings: 'Settings',
  summary: 'Edit About Me',
  experience: 'Experience Level',
  role: 'Add Experience',
  education: 'Add Education',
  skills: 'Edit Skills',
  uploadportfolio: 'Upload Portfolio',
  createportfolio: 'Create Portfolio',
  resume: 'Upload Resume'
};

const page = usePage()

// Add console.log to debug user data
console.log('User data:', page.props.user);

// Modal functions
const openModal = (type) => {
  modalType.value = type;
  loadUserData();
};

const closeModal = () => {
  modalType.value = '';
  form.reset();
  skillsInput.value = '';
  settingsForm.value = {
    currentEmail: props.user.email || '',
    newEmail: '',
    confirmNewEmail: '',
    currentPassword: '',
    newPassword: '',
    confirmNewPassword: '',
  };
  form.nextRole = {
    title: '',
    company: '',
    start_date: '',
    end_date: '',
    description: ''
  };
  form.nextEducation = {
    degree: '',
    institution: '',
    year: ''
  };
};

const saveModal = async () => {
  try {
    switch (modalType.value) {
      case 'profile':
        form.put(route('profile.update'), {
          preserveScroll: true,
          onSuccess: () => {
            closeModal();
            window.location.reload();
          }
        });
        break;
      case 'settings':
        
        form.put(route('profile.update'), {
          preserveScroll: true,
          onSuccess: () => {
            closeModal();
            window.location.reload();
            console.log('Submitting form:', form);
          }
        });
        break;
      case 'summary':
        await form.patch(route('profile.update'), {
          preserveScroll: true,
          onSuccess: () => {
            closeModal();
            window.location.reload();
          }
        });
        break;
      case 'experience':
        await form.patch(route('profile.update'), {
          preserveScroll: true,
          onSuccess: () => {
            closeModal();
            window.location.reload();
          }
        });
        break;
      case 'role':
        if (!form.nextRole.title || !form.nextRole.company || !form.nextRole.start_date) {
          alert('Please fill in all required fields (Title, Company, and Start Date)');
          return;
        }
        
        // Create a new array with existing experience plus new role
        const newExperience = [...form.experience];
        newExperience.push({
          title: form.nextRole.title,
          company: form.nextRole.company,
          start_date: form.nextRole.start_date,
          end_date: form.nextRole.end_date || null,
          description: form.nextRole.description || ''
        });

        try {
          await form.patch(route('profile.update'), {
            about_me: {
              ...props.aboutMe,
              career_history: newExperience
            }
          });
          form.experience = newExperience;
          closeModal();
          window.location.reload();
        } catch (error) {
          console.error('Error saving experience:', error);
        }
        break;
      case 'education':
        if (!form.nextEducation.degree || !form.nextEducation.institution || !form.nextEducation.year) {
          alert('Please fill in all education fields');
          return;
        }

        // Validate year
        const year = parseInt(form.nextEducation.year);
        if (isNaN(year) || year < 1900 || year > new Date().getFullYear()) {
          alert('Please enter a valid year between 1900 and current year');
          return;
        }

        // Create a new array with existing education plus new entry
        const newEducation = [...form.education];
        newEducation.push({
          degree: form.nextEducation.degree,
          institution: form.nextEducation.institution,
          year: year
        });

        try {
          await form.patch(route('profile.update'), {
            about_me: {
              ...props.aboutMe,
              education: newEducation
            }
          });
          form.education = newEducation;
          closeModal();
          window.location.reload();
        } catch (error) {
          console.error('Error saving education:', error);
        }
        break;
      case 'skills':
        await form.patch(route('profile.update'), {
          preserveScroll: true,
          onSuccess: () => {
            closeModal();
            skillsInput.value = '';
            window.location.reload();
          }
        });
        break;
      case 'uploadportfolio':
      case 'createportfolio':
        await savePortfolio();
        break;
    }
  } catch (error) {
    console.error('Error saving:', error);
  }

  console.log('Submitting form:', form);
};

// Portfolio functions
const addItem = (section) => {
  if (!form.portfolio[section]) {
    form.portfolio[section] = [];
  }
  
  const newItem = {
    title: '',
    description: '',
    file: null
  };
  
  form.portfolio[section].push(newItem);
};

const removeItem = (section, index) => {
  form.portfolio[section].splice(index, 1);
};

const handleFileSelect = (event, section, index) => {
  const files = event.target.files;
  if (files.length > 0) {
    if (modalType.value === 'uploadportfolio') {
      form.files = files;
    } else if (section && typeof index !== 'undefined') {
      form.portfolio[section][index].file = files[0];
    }
  }
};

const savePortfolio = async () => {
  try {
    if (modalType.value === 'uploadportfolio' && form.files) {
      const formData = new FormData();
      
      // Handle file uploads
      Array.from(form.files).forEach((file, index) => {
        formData.append(`files[${index}]`, file);
      });
      
      await axios.post(route('profile.portfolio.upload'), formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then(() => {
        closeModal();
        window.location.reload();
      });
      
    } else if (modalType.value === 'createportfolio') {
      const formData = new FormData();
      
      // Handle portfolio creation with sections
      Object.keys(form.portfolio).forEach(section => {
        form.portfolio[section].forEach((item, index) => {
          if (item.file) {
            formData.append(`${section}[${index}][file]`, item.file);
          }
          Object.keys(item).forEach(key => {
            if (key !== 'file') {
              formData.append(`${section}[${index}][${key}]`, item[key]);
            }
          });
        });
      });
      
      await axios.post(route('profile.portfolio.create'), formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then(() => {
        closeModal();
        window.location.reload();
      });
    }
  } catch (error) {
    console.error('Error saving portfolio:', error);
  }
};

const editPortfolioItem = (item) => {
  // Implement edit portfolio item functionality
  modalType.value = 'editportfolio';
  // Set the current item data for editing
};

const deletePortfolioItem = (id) => {
  if (confirm('Are you sure you want to delete this portfolio item?')) {
    form.delete(route('profile.portfolio.delete', { portfolio: id }), {
      preserveScroll: true,
      onSuccess: () => {
        // Refresh portfolio items
        portfolioItems.value = portfolioItems.value.filter(item => item.id !== id);
      },
    });
  }
};

const handlePhotoUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.profile_photo = file;
    form.post(route('profile.update'), {
      preserveScroll: true,
      onSuccess: () => closeModal(),
    });
  }
};

const deleteAccount = () => {
  if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
    form.delete(route('profile.destroy'), {
      preserveScroll: true,
      onSuccess: () => {
        // Handle successful deletion
      },
    });
  }
};

// Add this function after the imports
const capitalizeFirst = (str) => {
  if (!str) return '';
  return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
};

// Add these functions for skills management
const addSkill = () => {
  if (skillsInput.value.trim()) {
    if (!form.skills) {
      form.skills = [];
    }
    form.skills.push(skillsInput.value.trim());
    skillsInput.value = '';
  }
};

const removeSkill = (index) => {
  form.skills.splice(index, 1);
};

// Add this function to load user data when opening modals
const loadUserData = () => {
  if (modalType.value === 'profile') {
    form.graduate_first_name = props.user?.graduate_first_name || '';
    form.graduate_middle_initial = props.user?.graduate_middle_initial || '';
    form.graduate_last_name = props.user?.graduate_last_name || '';
    form.email = props.user?.email || '';
    form.professional_title = props.user?.graduate_professional_title || '';
  } else if (modalType.value === 'summary') {
    form.personal_summary = props.aboutMe?.personal_summary || '';
  } else if (modalType.value === 'experience') {
    form.experience_level = props.aboutMe?.experience_level || '';
    form.experience = props.aboutMe?.career_history || [];
  } else if (modalType.value === 'education') {
    form.education = props.aboutMe?.education || [];
  } else if (modalType.value === 'skills') {
    form.skills = props.aboutMe?.skills || [];
  }
};

// Format date helper function
const formatDate = (date) => {
  if (!date) return '';
  return new Date(date).toLocaleDateString();
};

// Preview portfolio item
const previewPortfolioItem = (item) => {
  // Implement preview functionality
  console.log('Preview item:', item);
  // You can add modal preview logic here
};

// Add formatExperienceLevel helper function
const formatExperienceLevel = (level) => {
  const levels = {
    'entry': 'Entry Level',
    'mid': 'Mid Level',
    'senior': 'Senior Level',
    'lead': 'Lead',
    'manager': 'Manager',
    'director': 'Director',
    'executive': 'Executive'
  };
  return levels[level] || level;
};
</script>



<template>
  <Graduate>
    <!-- About Me Section Container -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
      <div class="flex items-start justify-between">
        <div class="flex items-center space-x-4">
          <div class="relative">
            <div class="space-y-2">
              <h1 class="text-2xl font-bold"  v-if="$page.props.user">
                {{ capitalizeFirst($page.props.auth.user.graduate_first_name) }} 
                <span v-if="$page.props.auth.user.role === 'graduate'">{{ capitalizeFirst($page.props.auth.user.graduate_middle_initial) }}</span>
                {{ capitalizeFirst($page.props.auth.user.graduate_last_name) }}
              </h1>
              <p class="text-lg">{{ form.graduate_professional_title || 'Add your professional title' }}</p>
              <div class="flex items-center text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
        </svg>
                {{ $page.props.auth.user.email }}
      </div>
        </div>
      </div>
        </div>
        <div class="flex space-x-2">
          <button @click="openModal('profile')" 
                  class="px-4 py-2 rounded-lg transition flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
            Edit Profile
          </button>
          <button @click="openModal('settings')" 
                  class="px-4 py-2 rounded-lg transition flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
        </svg>
            Settings
          </button>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Main Content -->
      <div class="lg:col-span-2 space-y-6">
        <!-- About Me Section -->
        <div class="bg-white shadow-lg rounded-lg p-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">About Me</h2>
            <button @click="openModal('summary')" 
                    class="transition flex items-center">
              <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
              </svg>
              Edit
            </button>
        </div>
          <p class="whitespace-pre-line">{{ form.graduate_personal_summary || 'Add a brief summary about yourself...' }}</p>
      </div>

        <!-- Experience Section -->
        <div class="bg-white shadow-lg rounded-lg p-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Experience</h2>
            <div class="flex space-x-2">
              <button @click="openModal('experience')" 
                      class="px-4 py-2 rounded-lg transition flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Experience Level
              </button>
              <button @click="openModal('role')" 
                      class="px-4 py-2 rounded-lg transition flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Experience
              </button>
        </div>
      </div>

          <!-- Display Experience Level -->
          <div v-if="form.experience_level" class="mb-4 p-4 bg-gray-50 rounded-lg">
            <h3 class="font-medium text-gray-700">Experience Level:</h3>
            <p class="mt-1 text-gray-600">{{ formatExperienceLevel(form.graduate_experience_level) }}</p>
        </div>

          <!-- Display Experience List -->
          <div v-if="form.experience && form.experience.length > 0" class="mt-4 space-y-4">
            <div v-for="(exp, index) in form.experience" :key="index" 
                 class="border-l-4 border-indigo-500 pl-4 py-3 bg-white rounded-lg shadow-sm">
              <h3 class="font-semibold text-lg text-gray-800">{{ exp.title }}</h3>
              <p class="text-gray-700 font-medium">{{ exp.company }}</p>
              <p class="text-sm text-gray-600">
                {{ formatDate(exp.start_date) }} - {{ exp.end_date ? formatDate(exp.end_date) : 'Present' }}
              </p>
              <p v-if="exp.description" class="mt-2 text-gray-600">{{ exp.description }}</p>
        </div>
          </div>
          <p v-else class="mt-1 text-gray-600">Add your work experience</p>
    </div>

        <!-- Portfolio Section -->
        <div class="bg-white shadow-lg rounded-lg p-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Portfolio</h2>
            <div class="flex space-x-2">
              <button @click="openModal('uploadportfolio')" 
                      class="px-4 py-2 rounded-lg transition flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                Upload
              </button>
              <button @click="openModal('createportfolio')" 
                      class="px-4 py-2 rounded-lg transition flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create
              </button>
        </div>
      </div>

          <!-- Portfolio Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <template v-if="portfolioItems && portfolioItems.length > 0">
              <div v-for="item in portfolioItems" :key="item.id" 
                   class="bg-white rounded-lg shadow-md overflow-hidden group">
                <!-- Portfolio Item Image -->
                <div class="relative h-48 overflow-hidden">
                  <img :src="item.image || '/placeholder-image.jpg'" 
                       :alt="item.title"
                       class="w-full h-full object-cover"
                       @error="$event.target.src = '/placeholder-image.jpg'">
        </div>

                <!-- Portfolio Item Content -->
                <div class="p-4">
                  <h3 class="text-lg font-semibold mb-2">{{ item.title || 'Untitled Project' }}</h3>
                  <p class="text-sm mb-4 line-clamp-2">{{ item.description || 'No description available' }}</p>
                  
                  <!-- Portfolio Item Tags -->
                  <div v-if="item.tags && item.tags.length" class="flex flex-wrap gap-2 mb-4">
                    <span v-for="tag in item.tags" 
                          :key="tag"
                          class="px-2 py-1 text-xs bg-gray-100 rounded-full">
                      {{ tag }}
                    </span>
      </div>

                  <!-- Portfolio Item Actions -->
                  <div class="flex justify-between items-center">
                    <div class="flex space-x-2">
                      <button @click="previewPortfolioItem(item)" 
                              class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                      </button>
                      <button @click="editPortfolioItem(item)" 
                              class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                      </button>
                      <button @click="deletePortfolioItem(item.id)" 
                              class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                      </button>
        </div>
                    <span class="text-xs text-gray-500">{{ formatDate(item.created_at) }}</span>
      </div>
                </div>
              </div>
            </template>

            <!-- Empty State -->
            <div v-else class="lg:col-span-3 text-center py-12">
              <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              <h3 class="mt-2 text-lg font-medium text-gray-900">No portfolio items yet</h3>
              <p class="mt-1 text-sm text-gray-500">Get started by clicking the Upload or Create button above.</p>
            </div>
        </div>
      </div>
    </div>

      <!-- Sidebar -->
      <div class="space-y-6">
        <!-- Education Section -->
        <div class="bg-white shadow-lg rounded-lg p-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Education</h2>
            <button @click="openModal('education')" 
                    class="transition flex items-center">
              <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
              </svg>
              Add
            </button>
          </div>
          <h3 class="font-medium text-gray-700">Education:</h3>
          <div v-if="form.education && form.education.length > 0" class="mt-4 space-y-4">
            <div v-for="(edu, index) in form.education" :key="index" 
                 class="border-l-4 border-indigo-500 pl-4 py-3 bg-white rounded-lg shadow-sm">
              <h3 class="font-semibold text-lg text-gray-800">{{ edu.degree }}</h3>
              <p class="text-gray-700 font-medium">{{ edu.institution }}</p>
              <p class="text-sm text-gray-600">{{ edu.year }}</p>
            </div>
          </div>
          <p v-else class="mt-1 text-gray-600">Add your educational background</p>
      </div>

        <!-- Skills Section -->
        <div class="bg-white shadow-lg rounded-lg p-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Skills</h2>
            <button @click="openModal('skills')" 
                    class="transition flex items-center">
              <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
              </svg>
              Edit
            </button>
          </div>
          <div v-if="form.skills && form.skills.length" class="flex flex-wrap gap-2">
            <span v-for="skill in form.skills" :key="skill" 
                  class="px-3 py-1 bg-gray-100 rounded-full text-sm">
              {{ skill }}
            </span>
          </div>
          <p v-else class="text-center py-4">Add your skills</p>
        </div>
      </div>
    </div>

        <!-- Modal Action Buttons -->
        <div class="mt-6 flex gap-2">
      <button class="px-4 py-2 rounded" @click="saveModal">Done</button>
      <button class="px-4 py-2 rounded" @click="closeModal">Cancel</button>
    </div>
  </Graduate>

    <!-- Edit About Me Modal -->
    <Modal :show="modalType === 'profile'" @close="closeModal">
      <div class="p-6">
        <h2 class="text-lg font-semibold">{{ modalTitles.profile }}</h2>
        <div class="col-span-6 sm:col-span-4">
          <InputLabel for="first_name" value="First Name" />
          <TextInput
            id="first_name"
          v-model="form.graduate_first_name"
            type="text"
            class="mt-1 block w-full"
            required
            autocomplete="given-name"
            placeholder="First Name" />
        <InputError :message="form.errors.graduate_first_name" class="mt-2 mb-5" />
      </div>
      
      <div class="col-span-6 sm:col-span-4">
        <InputLabel for="middle_initial" value="Middle Initial" />
        <TextInput
          id="middle_initial"
          v-model="form.graduate_middle_initial"
          type="text"
          class="mt-1 block w-full"
          autocomplete="middle-name"
          placeholder="Middle Initial" />
        <InputError :message="form.errors.graduate_middle_initial" class="mt-2 mb-5" />
        </div>
        
        <div class="col-span-6 sm:col-span-4">
          <InputLabel for="last_name" value="Last Name" />
          <TextInput
            id="last_name"
          v-model="form.graduate_last_name"
            type="text"
            class="mt-1 block w-full"
            required
            autocomplete="family-name"
            placeholder="Last Name" />
        <InputError :message="form.errors.graduate_last_name" class="mt-2 mb-5" />
        </div>
        
        <div class="col-span-6 sm:col-span-4">
          <InputLabel for="professional_title" value="Professional Title" />
          <TextInput
            id="professional_title"
            v-model="form.graduate_professional_title"
            type="text"
            class="mt-1 block w-full"
            placeholder="Professional Title" />
          <InputError :message="form.errors.professional_title" class="mt-2 mb-5" />
        </div>

        <div class="mt-6 flex gap-2">
        <button class="px-4 py-2 rounded" @click="saveModal">Done</button>
        <button class="px-4 py-2 rounded" @click="closeModal">Cancel</button>
        </div>
      </div>
    </Modal>

    <!-- Edit in Settings Modal -->
    <Modal :show="modalType === 'settings'" @close="closeModal">
      <div class="p-6">
        <h2 class="text-lg font-semibold">{{ modalTitles.settings }}</h2>
        
        <!-- Email Section -->
        <div class="col-span-6 sm:col-span-4 mb-4" @click="showEmailSettings = !showEmailSettings">
          <InputLabel for="email" value="Email" />
          <TextInput id="email" v-model="settingsForm.currentEmail" type="email" class="mt-1 block w-full" :placeholder="showEmailSettings ? 'Current Email' : settingsForm.currentEmail" readonly/>
        </div>
        
        <div v-if="showEmailSettings" class="mb-4">
          <TextInput id="new_email" v-model="settingsForm.email" type="email" class="mt-1 block w-full" placeholder="New Email" />
        </div>
        
        <!-- Password Section -->
        <div class="col-span-6 sm:col-span-4 mb-4" @click="showPasswordSettings= !showPasswordSettings">
          <InputLabel for="password" value="Password" />
          <TextInput id="password" type="password" class="mt-1 blo  ck w-full" placeholder="••••••••" readonly/>
        </div>
        
        <div v-if="showPasswordSettings" class="mb-4">
          <TextInput id="current_password" v-model="settingsForm.current_password" type="password" class="mt-1 block w-full" placeholder="Current Password" ref="currentPasswordInput" /> <InputError :message="settingsForm.errors.current_password" class="mt-2" />
          <TextInput id="new_password" v-model="settingsForm.password" type="password" class="mt-1 block w-full" placeholder="New Password" ref="passwordInput" /> <InputError :message="settingsForm.errors.password" class="mt-2" />
          <TextInput id="confirm_new_password" v-model="settingsForm.password_confirmation" type="password" class="mt-1 block w-full" placeholder="Confirm New Password" /> <InputError :message="settingsForm.errors.password_confirmation" class="mt-2" />
        </div>
        
        <!-- Delete Account -->
        <div class="col-span-6 sm:col-span-4">
        <button class="px-4 py-2 rounded mt-4" @click="deleteAccount">Delete Account</button>
        </div>

        <div class="mt-6 flex gap-2">
          <button class="px-4 py-2 rounded" @click="updatePassword">Change Password</button>

        <button class="px-4 py-2 rounded" @click="saveModal">Done</button>
        <button class="px-4 py-2 rounded" @click="closeModal">Cancel</button>
        </div>
      </div>
    </Modal>

  <!-- Edit Summary Modal -->
  <Modal :show="modalType === 'summary'" @close="closeModal">
      <div class="p-6">
      <h2 class="text-lg font-semibold">{{ modalTitles.summary }}</h2>
      <div class="mt-4">
        <TextArea
          v-model="form.personal_summary"
          class="mt-1 block w-full"
          rows="6"
          placeholder="Tell us about yourself..."
        />
        </div>
      <div class="mt-6 flex gap-2">
        <button class="px-4 py-2 rounded" @click="saveModal">Done</button>
        <button class="px-4 py-2 rounded" @click="closeModal">Cancel</button>
      </div>
        </div>
  </Modal>

        <!-- Experience Level Modal -->
  <Modal :show="modalType === 'experience'" @close="closeModal">
    <div class="p-6">
      <h2 class="text-lg font-semibold">{{ modalTitles.experience }}</h2>
      <div class="mt-4">
        <InputLabel for="experience_level" value="Experience Level" />
        <SelectInput
          id="experience_level"
          v-model="form.experience_level"
          class="mt-1 block w-full"
        >
          <option value="">Select Experience Level</option>
          <option value="entry">Entry Level</option>
          <option value="mid">Mid Level</option>
          <option value="senior">Senior Level</option>
          <option value="lead">Lead</option>
          <option value="manager">Manager</option>
          <option value="director">Director</option>
          <option value="executive">Executive</option>
        </SelectInput>
        </div>
      <div class="mt-6 flex gap-2">
        <button class="px-4 py-2 rounded" @click="saveModal">Done</button>
        <button class="px-4 py-2 rounded" @click="closeModal">Cancel</button>
            </div>
          </div>
  </Modal>
          
  <!-- Add Role Modal -->
  <Modal :show="modalType === 'role'" @close="closeModal">
    <div class="p-6">
      <h2 class="text-lg font-semibold">{{ modalTitles.role }}</h2>
          <div class="mt-4">
        <InputLabel for="title" value="Job Title" />
        <TextInput
          id="title"
          v-model="form.nextRole.title"
          type="text"
          class="mt-1 block w-full"
          placeholder="e.g. Software Engineer"
        />
          </div>
      <div class="mt-4">
        <InputLabel for="company" value="Company" />
        <TextInput
          id="company"
          v-model="form.nextRole.company"
          type="text"
          class="mt-1 block w-full"
          placeholder="e.g. Tech Corp"
        />
        </div>
      <div class="mt-4">
        <InputLabel for="start_date" value="Start Date" />
        <TextInput
          id="start_date"
          v-model="form.nextRole.start_date"
          type="date"
          class="mt-1 block w-full"
        />
            </div>
      <div class="mt-4">
        <InputLabel for="end_date" value="End Date" />
        <TextInput
          id="end_date"
          v-model="form.nextRole.end_date"
          type="date"
          class="mt-1 block w-full"
        />
            </div>
      <div class="mt-4">
        <InputLabel for="description" value="Description" />
        <TextArea
          id="description"
          v-model="form.nextRole.description"
          class="mt-1 block w-full"
          rows="4"
          placeholder="Describe your role and responsibilities..."
        />
          </div>
      <div class="mt-6 flex gap-2">
        <button class="px-4 py-2 rounded" @click="saveModal">Done</button>
        <button class="px-4 py-2 rounded" @click="closeModal">Cancel</button>
          </div>
    </div>
  </Modal>

  <!-- Add Education Modal -->
  <Modal :show="modalType === 'education'" @close="closeModal">
    <div class="p-6">
      <h2 class="text-lg font-semibold">{{ modalTitles.education }}</h2>
      <div class="mt-4">
        <InputLabel for="degree" value="Degree" />
        <TextInput
          id="degree"
          v-model="form.nextEducation.degree"
          type="text"
          class="mt-1 block w-full"
          placeholder="e.g. Bachelor of Science"
        />
            </div>
      <div class="mt-4">
        <InputLabel for="institution" value="Institution" />
        <TextInput
          id="institution"
          v-model="form.nextEducation.institution"
          type="text"
          class="mt-1 block w-full"
          placeholder="e.g. University of Technology"
        />
            </div>
          <div class="mt-4">
        <InputLabel for="year" value="Year" />
        <TextInput
          id="year"
          v-model="form.nextEducation.year"
          type="number"
          min="1900"
          :max="new Date().getFullYear()"
          class="mt-1 block w-full"
          placeholder="e.g. 2020"
        />
          </div>
      <div class="mt-6 flex gap-2">
        <button class="px-4 py-2 rounded" @click="saveModal">Done</button>
        <button class="px-4 py-2 rounded" @click="closeModal">Cancel</button>
        </div>
          </div>
  </Modal>
          
  <!-- Edit Skills Modal -->
  <Modal :show="modalType === 'skills'" @close="closeModal">
    <div class="p-6">
      <h2 class="text-lg font-semibold">{{ modalTitles.skills }}</h2>
      <div class="mt-4">
        <InputLabel for="skills_input" value="Add Skill" />
          <div class="flex mt-3">
          <TextInput
            id="skills_input"
            v-model="skillsInput"
            type="text"
            class="mt-1 block w-full rounded-r-none"
            placeholder="e.g. JavaScript"
          />
          <button @click="addSkill" class="px-4 py-2 rounded-l-none">Add</button>
          </div>
        
        <div class="mt-4">
          <h3 class="text-sm font-semibold mb-2">Current Skills</h3>
          <div class="flex flex-wrap gap-2">
            <div v-for="(skill, index) in form.skills" :key="index" 
                 class="flex items-center bg-gray-100 rounded-full px-3 py-1">
              <span class="mr-2">{{ skill }}</span>
              <button @click="removeSkill(index)" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
          </div>
        </div>
      </div>
      <div class="mt-6 flex gap-2">
        <button class="px-4 py-2 rounded" @click="saveModal">Done</button>
        <button class="px-4 py-2 rounded" @click="closeModal">Cancel</button>
      </div>
    </div>
  </Modal>

        <!-- Upload Portfolio Modal -->
  <Modal :show="modalType === 'uploadportfolio'" @close="closeModal">
    <div class="p-6">
      <h2 class="text-lg font-semibold">{{ modalTitles.uploadportfolio }}</h2>
      <div class="mt-4">
        <InputLabel for="portfolio_files" value="Upload Files" />
        <div class="mt-2">
          <input
            type="file"
            id="portfolio_files"
            multiple
            @change="handleFileSelect"
            class="block w-full"
          />
            </div>
          </div>
      <div class="mt-6 flex gap-2">
        <button class="px-4 py-2 rounded" @click="savePortfolio">Upload</button>
        <button class="px-4 py-2 rounded" @click="closeModal">Cancel</button>
        </div>
    </div>
  </Modal>

        <!-- Create Portfolio Modal -->
  <Modal :show="modalType === 'createportfolio'" @close="closeModal">
    <div class="p-6">
      <h2 class="text-lg font-semibold">{{ modalTitles.createportfolio }}</h2>
      
      <!-- Work Samples Section -->
      <div class="mt-6">
        <h3 class="text-md font-semibold mb-4">Work Samples</h3>
        <div v-for="(sample, index) in form.portfolio.workSamples" :key="index" class="mb-4 p-4 border rounded">
          <div class="flex justify-between items-center mb-4">
            <h4 class="font-medium">Project {{ index + 1 }}</h4>
            <button @click="removeItem('workSamples', index)" class="text-red-500">Remove</button>
          </div>
          <div class="space-y-4">
            <div>
              <InputLabel for="work_title" value="Project Title" />
              <TextInput
                id="work_title"
                v-model="sample.title"
                type="text"
                class="mt-1 block w-full"
                placeholder="Enter project title"
              />
          </div>
            <div>
              <InputLabel for="work_description" value="Project Description" />
              <TextArea
                id="work_description"
                v-model="sample.description"
                class="mt-1 block w-full"
                rows="3"
                placeholder="Describe your project..."
              />
        </div>
            <div>
              <InputLabel for="work_file" value="Upload Work Sample" />
              <input
                type="file"
                id="work_file"
                @change="(e) => handleFileSelect(e, 'workSamples', index)"
                class="mt-1 block w-full"
                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
              />
            </div>
          </div>
        </div>
        <button @click="addItem('workSamples')" class="mt-2">Add Another Work Project</button>
        </div>

      <!-- Awards and Honors Section -->
      <div class="mt-6">
        <h3 class="text-md font-semibold mb-4">Awards and Honors</h3>
        <div v-for="(award, index) in form.portfolio.awards" :key="index" class="mb-4 p-4 border rounded">
          <div class="flex justify-between items-center mb-4">
            <h4 class="font-medium">Award {{ index + 1 }}</h4>
            <button @click="removeItem('awards', index)" class="text-red-500">Remove</button>
          </div>
          <div class="space-y-4">
            <div>
              <InputLabel for="award_name" value="Award Name" />
              <TextInput
                id="award_name"
                v-model="award.name"
                type="text"
                class="mt-1 block w-full"
                placeholder="Enter award name"
              />
            </div>
            <div>
              <InputLabel for="award_certificate" value="Upload Award Certificate" />
              <input
                type="file"
                id="award_certificate"
                @change="(e) => handleFileSelect(e, 'awards', index)"
                class="mt-1 block w-full"
                accept=".pdf,.jpg,.jpeg,.png"
              />
            </div>
          </div>
          </div>
        <button @click="addItem('awards')" class="mt-2">Add Another Award</button>
        </div>

      <!-- Documents Section -->
      <div class="mt-6">
        <h3 class="text-md font-semibold mb-4">Transcripts, Degrees, Licenses, and Certifications</h3>
        <div v-for="(doc, index) in form.portfolio.documents" :key="index" class="mb-4 p-4 border rounded">
          <div class="flex justify-between items-center mb-4">
            <h4 class="font-medium">Document {{ index + 1 }}</h4>
            <button @click="removeItem('documents', index)" class="text-red-500">Remove</button>
          </div>
          <div class="space-y-4">
          <div>
              <InputLabel for="doc_name" value="Document Name" />
              <TextInput
                id="doc_name"
                v-model="doc.name"
                type="text"
                class="mt-1 block w-full"
                placeholder="Enter document name"
              />
            </div>
            <div>
              <InputLabel for="doc_file" value="Upload Document" />
              <input
                type="file"
                id="doc_file"
                @change="(e) => handleFileSelect(e, 'documents', index)"
                class="mt-1 block w-full"
                accept=".pdf,.doc,.docx"
              />
          </div>
        </div>
        </div>
        <button @click="addItem('documents')" class="mt-2">Add Another Document</button>
        </div>

      <!-- Professional Development Section -->
      <div class="mt-6">
        <h3 class="text-md font-semibold mb-4">Professional Development</h3>
        <div v-for="(course, index) in form.portfolio.professionalDevelopment" :key="index" class="mb-4 p-4 border rounded">
          <div class="flex justify-between items-center mb-4">
            <h4 class="font-medium">Course/Workshop {{ index + 1 }}</h4>
            <button @click="removeItem('professionalDevelopment', index)" class="text-red-500">Remove</button>
          </div>
          <div class="space-y-4">
            <div>
              <InputLabel for="course_name" value="Course/Workshop Name" />
              <TextInput
                id="course_name"
                v-model="course.name"
                type="text"
                class="mt-1 block w-full"
                placeholder="Enter course/workshop name"
              />
        </div>
            <div>
              <InputLabel for="course_certificate" value="Upload Certificate" />
              <input
                type="file"
                id="course_certificate"
                @change="(e) => handleFileSelect(e, 'professionalDevelopment', index)"
                class="mt-1 block w-full"
                accept=".pdf,.jpg,.jpeg,.png"
              />
            </div>
          </div>
        </div>
        <button @click="addItem('professionalDevelopment')" class="mt-2">Add Another Course/Workshop</button>
        </div>

      <!-- Volunteer Work Section -->
      <div class="mt-6">
        <h3 class="text-md font-semibold mb-4">Volunteer Work</h3>
        <div v-for="(volunteer, index) in form.portfolio.volunteerWork" :key="index" class="mb-4 p-4 border rounded">
          <div class="flex justify-between items-center mb-4">
            <h4 class="font-medium">Experience {{ index + 1 }}</h4>
            <button @click="removeItem('volunteerWork', index)" class="text-red-500">Remove</button>
            </div>
          <div class="space-y-4">
            <div>
              <InputLabel for="volunteer_role" value="Role/Activity" />
              <TextInput
                id="volunteer_role"
                v-model="volunteer.role"
                type="text"
                class="mt-1 block w-full"
                placeholder="Enter role/activity"
              />
          </div>
          <div>
              <InputLabel for="volunteer_certificate" value="Upload Certificate (if any)" />
              <input
                type="file"
                id="volunteer_certificate"
                @change="(e) => handleFileSelect(e, 'volunteerWork', index)"
                class="mt-1 block w-full"
                accept=".pdf,.jpg,.jpeg,.png"
              />
            </div>
          </div>
        </div>
        <button @click="addItem('volunteerWork')" class="mt-2">Add Another Volunteer Experience</button>
        </div>

      <!-- Professional Memberships Section -->
      <div class="mt-6">
        <h3 class="text-md font-semibold mb-4">Professional Memberships</h3>
        <div v-for="(membership, index) in form.portfolio.professionalMemberships" :key="index" class="mb-4 p-4 border rounded">
          <div class="flex justify-between items-center mb-4">
            <h4 class="font-medium">Membership {{ index + 1 }}</h4>
            <button @click="removeItem('professionalMemberships', index)" class="text-red-500">Remove</button>
          </div>
          <div class="space-y-4">
            <div>
              <InputLabel for="org_name" value="Organization Name" />
              <TextInput
                id="org_name"
                v-model="membership.organization"
                type="text"
                class="mt-1 block w-full"
                placeholder="Enter organization name"
              />
            </div>
            <div>
              <InputLabel for="membership_certificate" value="Upload Membership Certificate (if any)" />
              <input
                type="file"
                id="membership_certificate"
                @change="(e) => handleFileSelect(e, 'professionalMemberships', index)"
                class="mt-1 block w-full"
                accept=".pdf,.jpg,.jpeg,.png"
              />
            </div>
          </div>
        </div>
        <button @click="addItem('professionalMemberships')" class="mt-2">Add Another Membership</button>
        </div>

      <!-- Modal Actions -->
        <div class="mt-6 flex gap-2">
        <button class="px-4 py-2 rounded" @click="savePortfolio">Submit</button>
        <button class="px-4 py-2 rounded" @click="closeModal">Cancel</button>
        </div>
      </div>
    </Modal>

  <!-- Add Resume Modal -->
  <Modal :show="modalType === 'resume'" @close="closeModal">
    <div class="p-6">
      <h2 class="text-lg font-semibold">{{ modalTitles.resume }}</h2>
      <div class="mt-4">
        <InputLabel for="resume_file" value="Upload Resume" />
        <div class="mt-2">
          <input
            type="file"
            id="resume_file"
            @change="handleFileSelect"
            class="block w-full"
            accept=".pdf,.doc,.docx"
          />
    </div>
      </div>
      <div class="mt-6 flex gap-2">
        <button class="px-4 py-2 rounded" @click="saveModal">Upload</button>
        <button class="px-4 py-2 rounded" @click="closeModal">Cancel</button>
      </div>
    </div>
  </Modal>
</template>

