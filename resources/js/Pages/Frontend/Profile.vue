<script setup>
import Graduate from '@/Layouts/AppLayout.vue';
import { ref, computed, onMounted, watch  } from 'vue';
import Modal from '@/Components/Modal.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import TextArea from '@/Components/TextArea.vue';
import SelectInput from '@/Components/SelectInput.vue';
import axios from 'axios';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Datepicker from 'vue3-datepicker';
import '@fortawesome/fontawesome-free/css/all.css';

const datepickerConfig = {
  format: 'YYYY-MM-DD',
  enableTime: false,
};

const formatDate = (date) => {
  if (!date) return '';
  return new Date(date).toISOString().split('T')[0];
};

const formattedStartDate = computed(() => {
  return education.value.graduate_education_start_date
    ? new Date(education.value.graduate_education_start_date).toISOString().split('T')[0]
    : null;
});

const formattedEndDate = computed(() => {
  return education.value.graduate_education_end_date
    ? new Date(education.value.graduate_education_end_date).toISOString().split('T')[0]
    : null;
});

// State Management
const stillInRole = ref(false);
const isDeleteConfirmationModalOpen = ref(false);
const itemToDelete = ref(null);
const activeSection = ref(localStorage.getItem('activeSection') || 'general');
const setActiveSection = (section) => {
  console.log('Active Section:', section);
  activeSection.value = section;
  localStorage.setItem('activeSection', section);
};

// Profile Data
const { props } = usePage();
const user = ref(props.user || {});
const profile = ref({
  fullName: `${props.user?.graduate_first_name || ''} ${props.user?.graduate_middle_initial || ''} ${props.user?.graduate_last_name || ''}`.trim(),
  graduate_first_name: props.user?.graduate_first_name || '',
  graduate_middle_initial: props.user?.graduate_middle_initial || '',
  graduate_last_name: props.user?.graduate_last_name || '',
  graduate_professional_title: props.user?.graduate_professional_title || '',
  email: props.user?.email || '',
  graduate_phone: props.user?.contact_number || '',
  graduate_location: props.user?.graduate_location || '',
  graduate_birthdate: props.user?.dob ? new Date(props.user.dob) : null,
  graduate_gender: props.user?.gender || '',
  graduate_ethnicity: props.user?.graduate_ethnicity || '',
  graduate_address: props.user?.graduate_address || '',
  graduate_about_me: props.user?.graduate_about_me || '',
  graduate_picture_url: props.user?.profile_picture || 'path/to/default/image.jpg',
});

const settingsForm = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
  graduate_first_name: profile.value.graduate_first_name,
  graduate_middle_initial: profile.value.graduate_middle_initial,
  graduate_last_name: profile.value.graduate_last_name,
  graduate_professional_title: profile.value.graduate_professional_title,
  email: profile.value.email,
  graduate_phone: profile.value.graduate_phone,
  graduate_location: profile.value.graduate_location,
  dob: profile.value.dob,
  graduate_gender: profile.value.graduate_gender,
  graduate_ethnicity: profile.value.graduate_ethnicity,
  graduate_address: profile.value.graduate_address,
  graduate_about_me: profile.value.graduate_about_me,
  graduate_picture_url: profile.value.graduate_picture_url,
});


const parseFullName = () => {
  const fullName = profile.value.fullName.trim();
  const nameParts = fullName.split(' ');

  if (nameParts.length >= 1) {
    profile.value.graduate_first_name = nameParts[0];
  }
  if (nameParts.length >= 2) {
    profile.value.graduate_last_name = nameParts[nameParts.length - 1];
  }
  if (nameParts.length > 2) {
    profile.value.graduate_middle_initial = nameParts[1].charAt(0);
  }
};

// Password Management
const passwordInput = ref(null);
const currentPasswordInput = ref(null);

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

const validatePassword = (password) => {
  const minLength = 8;
  const hasUpperCase = /[A-Z]/.test(password);
  const hasLowerCase = /[a-z]/.test(password);
  const hasNumber = /[0-9]/.test(password);
  const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);
  return password.length >= minLength && hasUpperCase && hasLowerCase && hasNumber && hasSpecialChar;
};

const currentPassword = ref('');
const newPassword = ref('');
const confirmPassword = ref('');


// Education Data
const educationEntries = ref(props.educationEntries || []);
const education = ref({
  graduate_education_institution_id: '',
  graduate_education_program: '',
  graduate_education_field_of_study: '',
  graduate_education_start_date: null,
  graduate_education_end_date: null,
  graduate_education_description: '',
  is_current: false,
  achievements: '',
  noAchievements: false,
});

// Skills Data
const skills = ref(props.skillEntries || []);
const skillName = ref('');
const skillProficiencyType = ref('');
const skillType = ref('');
const yearsExperience = ref(0);
const isUpdateSkillModalOpen = ref(false);
let currentSkillIndex = ref(null);
const noExpiryDate = ref(false);

// Experience Data
const experienceEntries = ref([]);
const experience = ref({
  graduate_experience_title: '',
  graduate_experience_company: '',
  graduate_experience_start_date: null,
  graduate_experience_end_date: null,
  graduate_experience_address: '',
  graduate_experience_description: '',
  graduate_experience_employment_type: '',
  is_current: false,
});

// Project Dat
const noProjectUrl = ref(false);
const isOngoingProject = ref(false);
const newTech = ref('');
const projects = ref({
  graduate_projects_title: '',
  graduate_projects_description: '',
  graduate_projects_role: '',
  graduate_projects_start_date: '',
  graduate_projects_end_date: '',
  graduate_projects_url: '',
  graduate_projects_tech: [],
  graduate_projects_key_accomplishments: '',
  is_current: false,
});
const projectsEntries = ref([]);
let currentProjectIndex = ref(null);

// Certification Data
const certificationsEntries = ref(props.certificationsEntries || []);
const form = useForm({
  graduate_certification_name: '',
  graduate_certification_issuer: '',
  graduate_certification_issue_date: null,
  graduate_certification_expiry_date: null,
  graduate_certification_credential_url: '',
  avatar: null,
  noExpiryDate: false,
  noCredentialUrl: false,
  noCredentialUrl: false,
});

const certificationEntries = ref([]);
const certification = ref({
  graduate_certification_name: '',
  graduate_certification_issuer: '',
  graduate_certification_issue_date: null,
  graduate_certification_expiry_date: null,
  graduate_certification_credential_id: '',
  graduate_certification_credential_url: ''
});


// Achievement Data
const achievementsEntries = ref(props.achievementsEntries || []);
const achievements = ref({
  graduate_achievement_title: '',
  graduate_achievement_issuer: '',
  graduate_achievement_date: null,
  graduate_achievement_description: '',
  graduate_achievement_url: '',
  graduate_achievement_type: ''
});

const achievementEntries = ref([]);
const achievement = ref({
  graduate_achievement_title: '',
  graduate_achievement_issuer: '',
  graduate_achievement_date: null,
  graduate_achievement_description: '',
  graduate_achievement_url: '',
  graduate_achievement_type: ''
});

// Tedtimonials Data
const testimonialsEntries = ref(props.testimonialsEntries || []);
const testimonials = ref({
  graduate_testimonials_name: '',
  graduate_testimonials_role_title: '',
  graduate_testimonials_relationship: '',
  graduate_testimonials_testimonial: ''
});

// Testimonials Data
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

// Employment Data
const employmentEntries = ref(props.employmentEntries || []);
const employment = ref({
  graduate_employment_type: '',
  graduate_employment_salary_expectations: {
    min: '',
    max: '',
    currency: 'PESO',
    frequency: 'per year'
  },
  graduate_employment_preferred_locations: [],
  graduate_employment_work_environment: [],
  graduate_employment_availability: [],
  graduate_employment_additional_notes: ''
});

// Career Goals Data
const newIndustry = ref('');
const careerGoalsEntries = ref(props.careerGoalsEntries || []);
const careerGoals = ref({
  shortTermGoals: '',
  longTermGoals: '',
  industriesOfInterest: [],
  careerPath: ''
});




// Resume Data
const resumeEntries = ref(props.resumeEntries || []);
const resume = ref({
  file: null,
  fileName: ''
});

// Modal State
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
const isNoChangesModalOpen = ref(false);
const isAddIndustryModalOpen = ref(false);
let currentAchievementIndex = ref(null);
const isUpdateCertificationModalOpen = ref(false);
const isAddTestimonialsModalOpen = ref(false);
const isUpdateTestimonialsModalOpen = ref(false);
let currentTestimonialsIndex = ref(null);
const isAddLocationModalOpen = ref(false);
const isAddProjectModalOpen = ref(false);
const isUpdateProjectModalOpen = ref(false);
const ongoingProject = ref(false);
const noCredentialUrl = ref(false);

// Watchers
watch(() => profile.value.fullName, (newFullName) => {
  const nameParts = newFullName.trim().split(' ');
  profile.value.graduate_first_name = nameParts[0] || '';
  profile.value.graduate_last_name = nameParts[nameParts.length - 1] || '';
  profile.value.graduate_middle_initial = nameParts.length > 2 ? nameParts[1].charAt(0) : '';
});

watch(noExpiryDate, (newValue) => {
  if (newValue) {
    certification.value.graduate_certification_expiry_date = null;
  }
});

watch(() => education.value.graduate_education_start_date, (newValue) => {
  if (newValue && isNaN(new Date(newValue).getTime())) {
    education.value.graduate_education_start_date = null;
  }
});

watch(() => education.value.graduate_education_end_date, (newValue) => {

  console.log('Start Date:', newValue);

  if (newValue && isNaN(new Date(newValue).getTime())) {
    education.value.graduate_education_end_date = null;
  }
});

watch(() => education.value.graduate_education_end_date, (newValue) => {

  console.log('End Date:', newValue);

  if (newValue && isNaN(new Date(newValue).getTime())) {
    console.error('Invalid end date:', newValue);
    education.value.graduate_education_end_date = null;
  }
});

watch(() => profile.value.fullName, () => {
  parseFullName();
});

watch(noProjectUrl, (newValue) => {
  if (newValue) {
    projects.value.graduate_projects_url = '';
  }
});

watch(isOngoingProject, (newValue) => {
  if (newValue) {
    projects.value.graduate_projects_end_date = '';
  }
});

watch(() => projects.value.is_current, (newValue) => {
  if (newValue) {
    projects.value.graduate_projects_end_date = 'present';
  } else {
    projects.value.graduate_projects_end_date = null;
  }
});

watch(noCredentialUrl, (newValue) => {
  if (newValue) {
    certification.value.graduate_certification_credential_url = ''; 
  }
});

// Profile Handlers
const saveProfile = () => {
  if (profile.value.graduate_birthdate) {
    const date = new Date(profile.value.graduate_birthdate);
    settingsForm.dob = date.toISOString().split('T')[0];
  } else {
    settingsForm.dob = null;
  }
  settingsForm.graduate_first_name = profile.value.graduate_first_name;
  settingsForm.graduate_middle_initial = profile.value.graduate_middle_initial;
  settingsForm.graduate_last_name = profile.value.graduate_last_name;
  settingsForm.email = profile.value.email;
  settingsForm.graduate_phone = profile.value.graduate_phone;
  settingsForm.graduate_professional_title = profile.value.graduate_professional_title;
  settingsForm.graduate_location = profile.value.graduate_location;
  settingsForm.graduate_gender = profile.value.graduate_gender;
  settingsForm.graduate_ethnicity = profile.value.graduate_ethnicity;
  settingsForm.graduate_address = profile.value.graduate_address;
  settingsForm.graduate_about_me = profile.value.graduate_about_me;
  settingsForm.graduate_picture_url = profile.value.graduate_picture_url;
  const hasChanges = Object.keys(settingsForm.data()).some(
    (key) => settingsForm[key] !== profile.value[key]
  );
  if (!hasChanges) {
    isNoChangesModalOpen.value = true;
    return;

    console.log('Data sent to backend:', settingsForm.data());
  }
  settingsForm.post(route('profile.updateProfile'), {
    onSuccess: (response) => {
      Object.assign(profile.value, settingsForm.data());
      isProfileModalOpen.value = true;

      console.log('Profile saved successfully on the backend:', response.user);

    },
    onError: (errors) => {
      console.error('Error updating profile:', errors);
      alert('An error occurred while updating the profile. Please try again.');
    },
  });
};

// Password Handlers
const handleSubmit = () => {
  console.log('Current Password:', currentPassword.value);
  console.log('New Password:', newPassword.value);
  console.log('Confirm Password:', confirmPassword.value);
  isPasswordModalOpen.value = true;
};

// Education Handlers
const addEducation = () => {
  if (
    education.value.graduate_education_institution_id &&
    education.value.graduate_education_program &&
    education.value.graduate_education_field_of_study
  ) {
    const educationForm = useForm({
      graduate_education_institution_id: education.value.graduate_education_institution_id,
      graduate_education_program: education.value.graduate_education_program,
      graduate_education_field_of_study: education.value.graduate_education_field_of_study,
      graduate_education_start_date: education.value.graduate_education_start_date,
      graduate_education_end_date: education.value.is_current ? null : education.value.graduate_education_end_date, // Set to null if is_current is true
      graduate_education_description: education.value.graduate_education_description,
      is_current: education.value.is_current,
      achievements: education.value.noAchievements ? null : education.value.achievements,
    });

    console.log('Data sent to backend:', educationForm.data()); // Debugging: Check the data

    educationForm.post(route('education.add'), {
      onSuccess: (response) => {
        console.log('Education added successfully:', response);
        educationEntries.value.push({ ...educationForm.data(), id: response.id });
        resetEducation();
        isAddEducationModalOpen.value = false;
      },
      onError: (errors) => {
        console.error('Error adding education:', errors); // Debugging: Log the errors
        alert('An error occurred while adding education. Please try again.');
      },
    });
  } else {
    alert('Please fill in all required fields.');
  }
};

const updateEducation = () => {
  const educationForm = useForm({
    graduate_education_institution_id: education.value.graduate_education_institution_id,
    graduate_education_program: education.value.graduate_education_program,
    graduate_education_field_of_study: education.value.graduate_education_field_of_study,
    graduate_education_start_date: education.value.graduate_education_start_date,
    graduate_education_end_date: education.value.is_current ? 'present' : education.value.graduate_education_end_date,
    graduate_education_description: education.value.graduate_education_description,
    is_current: education.value.is_current,
    achievements: education.value.achievements,
    no_achievements: education.value.noAchievements, 
  });

  console.log('Updating education with data:', educationForm.data());

  educationForm.put(route('education.update', education.value.id), {
    onSuccess: () => {
      const index = educationEntries.value.findIndex(entry => entry.id === education.value.id);
      if (index !== -1) {
        educationEntries.value[index] = { ...educationForm.data(), id: education.value.id };
      }

      closeUpdateEducationModal();
      isEducationUpdatedModalOpen.value = true;
    },
    onError: (errors) => {
      console.error('Error updating education entry:', errors);
      alert('An error occurred while updating the education entry. Please try again.');
    },
  });
};

const handleIsCurrent = () => {
  if (education.value.is_current) {
    education.value.graduate_education_end_date = 'present';
  } else {
    education.value.graduate_education_end_date = null;
  }
};

const handleNoAchievements = () => {
  if (education.value.noAchievements) {
    education.value.achievements = '';
  }
};

// Skill Handlers
const saveSkill = () => {
  if (!skillName.value.trim()) {
    alert("Skill name cannot be empty.");
    return;
  }
  if (!skillProficiencyType.value) {
    alert("Please select a proficiency type.");
    return;
  }
  if (!skillType.value) {
    alert("Please select a skill type.");
    return;
  }
  if (yearsExperience.value < 0) {
    alert("Years of experience cannot be negative.");
    return;
  }

  const skillForm = useForm({
    graduate_skills_name: skillName.value,
    graduate_skills_proficiency_type: skillProficiencyType.value, // Ensure this is included
    graduate_skills_type: skillType.value,
    graduate_skills_years_experience: yearsExperience.value,
  });

  console.log('Data sent to backend for skill:', skillForm.data());
  console.log('Selected Proficiency Type:', skillProficiencyType.value);
  console.log('Selected Skill Type:', skillType.value);
  console.log('Years of Experience:', yearsExperience.value);

  skillForm.post(route('skills.add'), {
    onSuccess: (response) => {
      skills.value.push({ ...skillForm.data(), id: response.id });
      closeAddSkillModal();
      console.log('Skill added successfully:', response);
    },
    onError: (errors) => {
      console.error('Error adding skill:', errors);
      alert('An error occurred while adding the skill. Please try again.');
    },
  });
};



const skillForm = useForm({
    graduate_skills_name: skillName.value,
    graduate_skills_proficiency_type: skillProficiencyType.value,
    graduate_skills_type: skillType.value,
    graduate_skills_years_experience: yearsExperience.value,
});

console.log('Current Skill Index:', currentSkillIndex.value);
console.log('Skills Array:', skills.value);
console.log('Skill at Index:', skills.value[currentSkillIndex.value])

// Update Skill Handler
const updateSkill = () => {
  if (!skillProficiencyType.value) {
    alert("Please select a proficiency type.");
    return;
  }

  const duplicateSkill = skills.value.some(
    (skill, index) =>
      skill.graduate_skills_name.toLowerCase() === skillName.value.toLowerCase() &&
      index !== currentSkillIndex.value
  );

  if (duplicateSkill) {
    alert(`The skill "${skillName.value}" already exists.`);
    return;
  }

  const skillForm = useForm({
    graduate_skills_name: skillName.value,
    graduate_skills_proficiency_type: skillProficiencyType.value, // Ensure this is included
    graduate_skills_type: skillType.value,
    graduate_skills_years_experience: yearsExperience.value,
  });

  console.log('Updating skill with data:', skillForm.data());

  skillForm.put(route('skills.update', skills.value[currentSkillIndex.value].id), {
    onSuccess: () => {
      console.log('Skill updated successfully.');
      skills.value[currentSkillIndex.value] = { ...skillForm.data(), id: skills.value[currentSkillIndex.value].id };
      closeUpdateSkillModal();
    },
    onError: (errors) => {
      console.error('Error updating skill:', errors);
      alert('An error occurred while updating the skill. Please try again.');
    },
  });
};

const editSkill = (skill) => {
  skillName.value = skill.graduate_skills_name;
  skillProficiencyType.value = skill.graduate_skills_proficiency_type;
  skillType.value = skill.graduate_skills_type;
  yearsExperience.value = skill.graduate_skills_years_experience;
  currentSkillIndex.value = skills.value.indexOf(skill);
  isUpdateSkillModalOpen.value = true;
};

const closeAddSkillModal = () => {
  isAddSkillModalOpen.value = false;
  skillName.value = '';
  skillProficiencyType.value = '';
  skillType.value = '';
  yearsExperience.value = 0;
};

const newSkill = ref('');

const addSkillToExperience = () => {
  console.log('New Skill:', newSkill.value);
  console.log('Existing Skills:', experience.value.graduate_experience_skills_tech);

  if (newSkill.value.trim()) {
    // Check if the skill already exists in the comma-separated string
    const skills = experience.value.graduate_experience_skills_tech.split(',').map(skill => skill.trim());
    if (skills.includes(newSkill.value.trim())) {
      alert('This skill is already added.');
      return;
    }

    // Add the new skill to the comma-separated string
    if (experience.value.graduate_experience_skills_tech) {
      experience.value.graduate_experience_skills_tech += `, ${newSkill.value.trim()}`;
    } else {
      experience.value.graduate_experience_skills_tech = newSkill.value.trim();
    }

    console.log('Skill added to experience:', newSkill.value);
    newSkill.value = ''; // Clear the input
  } else {
    alert('Skill is empty.');
  }
};


// Experience Handlers
const addExperience = () => {
  if (
    experience.value.graduate_experience_title &&
    experience.value.graduate_experience_company &&
    experience.value.graduate_experience_employment_type &&
    experience.value.graduate_experience_start_date
  ) {
    const experienceForm = useForm({
      graduate_experience_title: experience.value.graduate_experience_title,
      graduate_experience_company: experience.value.graduate_experience_company,
      graduate_experience_employment_type: experience.value.graduate_experience_employment_type,
      graduate_experience_start_date: experience.value.graduate_experience_start_date,
      graduate_experience_end_date: experience.value.is_current ? null : experience.value.graduate_experience_end_date,
      graduate_experience_address: experience.value.graduate_experience_address,
      graduate_experience_description: experience.value.graduate_experience_description,
      is_current: experience.value.is_current,
    });

    // Log data being sent to the backend
    console.log('Data being sent to backend for adding experience:', experienceForm.data());

    experienceForm.post(route('experience.add'), {
      onSuccess: (response) => {
        console.log('Experience added successfully:', response);
        experienceEntries.value.push({ ...experienceForm.data(), id: response.id });
        resetExperience();
        isAddExperienceModalOpen.value = false;
      },
      onError: (errors) => {
        console.error('Error adding experience:', errors);
        alert('An error occurred while adding experience. Please try again.');
      },
    });
  } else {
    alert('Please fill in all required fields.');
  }
};

const updateExperience = () => {
  const experienceForm = useForm({
    graduate_experience_title: experience.value.graduate_experience_title,
    graduate_experience_company: experience.value.graduate_experience_company,
    graduate_experience_employment_type: experience.value.graduate_experience_employment_type,
    graduate_experience_start_date: experience.value.graduate_experience_start_date,
    graduate_experience_end_date: experience.value.is_current ? 'present' : experience.value.graduate_experience_end_date,
    graduate_experience_address: experience.value.graduate_experience_address,
    graduate_experience_description: experience.value.graduate_experience_description,
    is_current: experience.value.is_current,
  });

  // Log data being sent to the backend
  console.log('Data being sent to backend for updating experience:', experienceForm.data());

  experienceForm.put(route('experience.update', experience.value.id), {
    onSuccess: () => {
      console.log('Experience updated successfully.');
      const index = experienceEntries.value.findIndex(entry => entry.id === experience.value.id);
      if (index !== -1) {
        experienceEntries.value[index] = { ...experienceForm.data(), id: experience.value.id };
      }
      closeUpdateExperienceModal();
      isExperienceUpdatedModalOpen.value = true;
    },
    onError: (errors) => {
      console.error('Error updating experience entry:', errors);
      alert('An error occurred while updating the experience entry. Please try again.');
    },
  });
};


// Project Handlers
const addProject = () => {
  const projectForm = useForm({
    graduate_projects_title: projects.value.graduate_projects_title,
    graduate_projects_description: projects.value.graduate_projects_description || 'No description provided',
    graduate_projects_role: projects.value.graduate_projects_role,
    graduate_projects_start_date: projects.value.graduate_projects_start_date,
    graduate_projects_end_date: projects.value.is_current ? 'present' : projects.value.graduate_projects_end_date,
    graduate_projects_url: projects.value.graduate_projects_url,
    graduate_projects_tech: projects.value.graduate_projects_tech,
    graduate_projects_key_accomplishments: projects.value.graduate_projects_key_accomplishments,
    is_current: projects.value.is_current, // Added is_current
  });

  console.log('Data sent to backend for project:', projectForm.data());

  projectForm.post(route('projects.add'), {
    onSuccess: (response) => {
      projectsEntries.value.push({ ...projectForm.data(), id: response.id });
      resetProject();
      isAddProjectModalOpen.value = false;

      console.log('Project added successfully:', response);
    },
    onError: (errors) => {
      console.error('Error adding project:', errors);
      alert('An error occurred while adding the project. Please try again.');
    },
  });
};

const updateProject = () => {
  const projectForm = useForm({
    graduate_projects_title: projects.value.graduate_projects_title,
    graduate_projects_description: projects.value.graduate_projects_description || 'No description provided',
    graduate_projects_role: projects.value.graduate_projects_role,
    graduate_projects_start_date: projects.value.graduate_projects_start_date,
    graduate_projects_end_date: projects.value.is_current ? 'present' : projects.value.graduate_projects_end_date,
    graduate_projects_url: projects.value.graduate_projects_url,
    graduate_projects_tech: projects.value.graduate_projects_tech,
    graduate_projects_key_accomplishments: projects.value.graduate_projects_key_accomplishments,
    is_current: projects.value.is_current, // Added is_current
  });

  console.log('Updating project with data:', projectForm.data());

  projectForm.put(route('projects.update', projects.value.id), {
    onSuccess: () => {
      const index = projectsEntries.value.findIndex(entry => entry.id === projects.value.id);
      if (index !== -1) {
        projectsEntries.value[index] = { ...projectForm.data(), id: projects.value.id };
      }
      resetProject();
      isUpdateProjectModalOpen.value = false;
    },
    onError: (errors) => {
      console.error('Error updating project:', errors);
      alert('An error occurred while updating the project. Please try again.');
    },
  });
};



const openUpdateProjectModal = (entry) => {
  projects.value = { ...entry };
  isUpdateProjectModalOpen.value = true;
};

const closeAddProjectModal = () => {
  resetProject();
  isAddProjectModalOpen.value = false;
};

const closeUpdateProjectModal = () => {
  resetProject();
  isUpdateProjectModalOpen.value = false;
};

const addTechToProject = () => {
  if (newTech.value.trim()) {
    projects.value.graduate_projects_tech.push(newTech.value.trim());
    console.log('Tech added to project:', newTech.value);
    newTech.value = '';
  }
};

const removeTechFromProject = (index) => {
  projects.value.graduate_projects_tech.splice(index, 1);
  console.log('Tech removed from project at index:', index);
};

// Certification Handlers
const addCertification = () => {
  if (!certification.value.graduate_certification_name || 
      !certification.value.graduate_certification_issuer || 
      !certification.value.graduate_certification_issue_date) {
    alert("Please fill in all required fields.");
    return;
  }

  const certificationForm = useForm({
    graduate_certification_name: certification.value.graduate_certification_name,
    graduate_certification_issuer: certification.value.graduate_certification_issuer,
    graduate_certification_issue_date: certification.value.graduate_certification_issue_date,
    graduate_certification_expiry_date: noExpiryDate.value ? null : certification.value.graduate_certification_expiry_date,
    graduate_certification_credential_id: certification.value.graduate_certification_credential_id,
    graduate_certification_credential_url: noCredentialUrl.value ? null : certification.value.graduate_certification_credential_url,
    file: form.avatar
  });

  certificationForm.post(route('certifications.add'), {
    onSuccess: (response) => {
      certificationsEntries.value.push({ ...certificationForm.data(), id: response.id });
      resetForm();
      noExpiryDate.value = false;
      noCredentialUrl.value = false;
      isAddCertificationModalOpen.value = false;
    },
    onError: (errors) => {
      console.error('Error adding certification:', errors);
      alert('An error occurred while adding the certification. Please try again.');
    },
  });
};

const updateCertification = () => {
  const index = certificationsEntries.value.findIndex(
    (entry) => entry.id === form.id // Match by ID
  );

  if (index !== -1) {
    certificationsEntries.value[index] = { ...form };

    console.log('Certification updated:', form);
    resetForm();

    isUpdateCertificationModalOpen.value = false;
  } else {
    alert("Certification not found.");
  }
};


const closeUpdateCertificationModal = () => {
  isUpdateCertificationModalOpen.value = false;
  resetForm();
};

const resetForm = () => {
  form.reset();
};

// Achievement Handlers
const addAchievement = () => {
  if (
    achievement.value.graduate_achievement_title &&
    achievement.value.graduate_achievement_issuer &&
    achievement.value.graduate_achievement_date
  ) {
    const achievementForm = useForm({
      graduate_achievement_title: achievement.value.graduate_achievement_title,
      graduate_achievement_issuer: achievement.value.graduate_achievement_issuer,
      graduate_achievement_date: achievement.value.graduate_achievement_date,
      graduate_achievement_description: achievement.value.graduate_achievement_description,
      graduate_achievement_url: achievement.value.graduate_achievement_url,
      graduate_achievement_type: achievement.value.graduate_achievement_type
    });

    console.log('Data sent to backend for achievement:', achievementForm.data());

    achievementForm.post(route('achievements.add'), {
      onSuccess: (response) => {
        achievementEntries.value.push({ ...achievementForm.data(), id: response.id });
        resetAchievement();
        isAddAchievementModalOpen.value = false;

        console.log('Achievement added successfully:', response);
      },
      onError: (errors) => {
        console.error('Error adding achievement:', errors);
        alert('An error occurred while adding the achievement. Please try again.');
      },
    });
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

    console.log('Achievement updated:', updatedAchievement);

    resetAchievement();
    isUpdateAchievementModalOpen.value = false; // Close the Update Achievement modal
  } else {
    alert("Please fill in all required fields.");
  }
};


// Testimonials Handlers
const addTestimonials = () => {
  if (
    testimonials.value.graduate_testimonials_name.trim() &&
    testimonials.value.graduate_testimonials_role_title.trim() &&
    testimonials.value.graduate_testimonials_relationship.trim() &&
    testimonials.value.graduate_testimonials_testimonial.trim()
  ) {
    const testimonialsForm = useForm({
      graduate_testimonials_name: testimonials.value.graduate_testimonials_name,
      graduate_testimonials_role_title: testimonials.value.graduate_testimonials_role_title,
      graduate_testimonials_relationship: testimonials.value.graduate_testimonials_relationship,
      graduate_testimonials_testimonial: testimonials.value.graduate_testimonials_testimonial
    });

    console.log('Data sent to backend for testimonial:', testimonialsForm.data());

    testimonialsForm.post(route('testimonials.add'), {
      onSuccess: (response) => {
        testimonialsEntries.value.push({ ...testimonialsForm.data(), id: response.id });
        resetTestimonials();
        isAddTestimonialsModalOpen.value = false;

        console.log('Testimonial added successfully:', response);
      },
      onError: (errors) => {
        console.error('Error adding testimonial:', errors);
        alert('An error occurred while adding the testimonial. Please try again.');
      },
    });
  } else {
    alert("Please fill in all required fields.");
  }
};

// Update testimonials handler
const updateTestimonials = () => {
  const index = currentTestimonialsIndex.value;

  if (
    testimonials.value.graduate_testimonials_name.trim() &&
    testimonials.value.graduate_testimonials_role_title.trim() &&
    testimonials.value.graduate_testimonials_relationship.trim() &&
    testimonials.value.graduate_testimonials_testimonial.trim()
  ) {
    const updatedTestimonial = {
      id: testimonialsEntries.value[index].id,
      graduate_testimonials_name: testimonials.value.graduate_testimonials_name,
      graduate_testimonials_role_title: testimonials.value.graduate_testimonials_role_title,
      graduate_testimonials_relationship: testimonials.value.graduate_testimonials_relationship,
      graduate_testimonials_testimonial: testimonials.value.graduate_testimonials_testimonial
    };
    testimonialsEntries.value[index] = updatedTestimonial;

    console.log('Testimonial updated:', updatedTestimonial);

    resetTestimonials();
    isUpdateTestimonialsModalOpen.value = false;
  } else {
    alert("Please fill in all required fields.");
  }
};

// Employment Preferences Handlers
const saveEmploymentPreferences = () => {
  if (
    !employmentPreferences.value.jobTypes.length &&
    !employmentPreferences.value.salaryExpectations.min &&
    !employmentPreferences.value.salaryExpectations.max &&
    !employmentPreferences.value.preferredLocations.length &&
    !employmentPreferences.value.workEnvironment.length &&
    !employmentPreferences.value.availability.length &&
    !employmentPreferences.value.additionalNotes
  ) {
    alert("Please fill in at least one employment preference.");
    return;
  }

  const employmentForm = useForm({
    jobTypes: employmentPreferences.value.jobTypes,
    salaryExpectations: employmentPreferences.value.salaryExpectations,
    preferredLocations: employmentPreferences.value.preferredLocations,
    workEnvironment: employmentPreferences.value.workEnvironment,
    availability: employmentPreferences.value.availability,
    additionalNotes: employmentPreferences.value.additionalNotes
  });

  console.log('Saved Preferences:', savedPreferences);

  console.log('Data sent to backend for employment preferences:', employmentForm.data());

  employmentForm.post(route('employment.preferences.save'), {
    onSuccess: (response) => {
      console.log('Employment preferences saved successfully:', response);
    },
    onError: (errors) => {
      console.error('Error saving employment preferences:', errors);
      alert('An error occurred while saving the employment preferences. Please try again.');
    },
  });
};
const newLocation = ref('');

const addPreferredLocation = () => {
  if (!newLocation.value.trim()) {
    alert("Please enter a valid location.");
    return;
  }

  if (!employmentPreferences.value.preferredLocations.includes(newLocation.value.trim())) {
    employmentPreferences.value.preferredLocations.push(newLocation.value.trim());

    console.log('Is Add Location Modal Open:', isAddLocationModalOpen.value);

    console.log("Location added:", newLocation.value);

    alert("Location added successfully!");
  } else {
    alert("This location is already in your preferences.");
  }

  closeAddLocationModal();
};


// Career Goals Handlers
const saveCareerGoals = () => {
  if (!careerGoals.value.shortTermGoals || !careerGoals.value.longTermGoals) {
    alert("Please fill in both short-term and long-term goals.");
    return;
  }

  const careerGoalsForm = useForm({
    shortTermGoals: careerGoals.value.shortTermGoals,
    longTermGoals: careerGoals.value.longTermGoals,
    industriesOfInterest: careerGoals.value.industriesOfInterest,
    careerPath: careerGoals.value.careerPath,
  });
  console.log('Saved Career Goals:', savedCareerGoals);
  console.log("Data being sent to backend for career goals:", careerGoalsForm.data());

  careerGoalsForm.post(route('career.goals.save'), {
    onSuccess: (response) => {
      console.log("Backend response for career goals:", response);
    },
    onError: (errors) => {
      console.error("Error saving career goals:", errors);
      alert("An error occurred while saving the career goals. Please try again.");
    },
  });
};

const closeAddIndustryModal = () => {
  isAddIndustryModalOpen.value = false;
  newIndustry.value = '';
};

const addPreferredIndustry = () => {
  if (!newIndustry.value.trim()) {
    alert("Please enter a valid industry name.");
    return;
  }

  if (!careerGoals.value.industriesOfInterest.includes(newIndustry.value.trim())) {
    careerGoals.value.industriesOfInterest.push(newIndustry.value.trim());

    console.log("New Industry Added:", newIndustry.value);

    console.log("Updated Industries of Interest:", careerGoals.value.industriesOfInterest);

    const careerGoalsForm = useForm({
      industriesOfInterest: careerGoals.value.industriesOfInterest,
    });

    console.log("Data being sent to backend:", careerGoalsForm.data());

    careerGoalsForm.post(route('career.goals.save'), {
      onSuccess: (response) => {
        console.log("Backend response:", response);
        alert("Industry added successfully!");
      },
      onError: (errors) => {
        console.error("Error saving industry:", errors);
        alert("An error occurred while saving the industry. Please try again.");
      },
    });
  } else {
    alert("This industry is already in your preferences.");
  }

  closeAddIndustryModal();
};

// Resume Handlers
const uploadResume = (event) => {
  const file = event.target.files[0];
  if (file) {
    resume.value.file = file;
    resume.value.fileName = file.name;
    console.log('Resume uploaded:', file.name);
  }
};

const removeResume = () => {
  resume.value.file = null;
  resume.value.fileName = '';
  console.log('Resume removed.');
};

//  Reset Functions
const resetEducation = () => {
  education.value = {
    graduate_education_institution_id: '',
    graduate_education_program: '',
    graduate_education_field_of_study: '',
    graduate_education_start_date: null,
    graduate_education_end_date: null,
    graduate_education_description: '',
    is_current: false,
    achievements: '',
    noAchievements: false,
  };
  console.log('Education reset.');
};

const resetSkill = () => {
  skillName.value = '';
  skillProficiencyType.value = '';
  skillType.value = '';
  yearsExperience.value = 0;
  console.log('Skill reset.');
};

const resetExperience = () => {
  experience.value = {
    graduate_experience_title: '',
    graduate_experience_company: '',
    graduate_experience_start_date: null,
    graduate_experience_end_date: null,
    graduate_experience_address: '',
    graduate_experience_description: '',
    graduate_experience_skills_tech: []
  };
  stillInRole.value = false;
  console.log('Experience reset.');
};

const resetProject = () => {
  projects.value = {
    graduate_projects_title: '',
    graduate_projects_description: '',
    graduate_projects_role: '',
    graduate_projects_start_date: null,
    graduate_projects_end_date: null,
    graduate_projects_url: '',
    graduate_projects_tech: [],
    graduate_projects_key_accomplishments: '',
    is_current: false, // Reset is_current
  };
  noProjectUrl.value = false;
  isOngoingProject.value = false;
  console.log('Project reset.');
};

const resetCertification = () => {
  certification.value = {
    graduate_certification_name: '',
    graduate_certification_issuer: '',
    graduate_certification_issue_date: null,
    graduate_certification_expiry_date: null,
    graduate_certification_credential_id: '',
    graduate_certification_credential_url: ''
  };
  console.log('Certification reset.');
};

const resetAchievement = () => {
  achievement.value = {
    graduate_achievement_title: '',
    graduate_achievement_issuer: '',
    graduate_achievement_date: null,
    graduate_achievement_description: '',
    graduate_achievement_url: '',
    graduate_achievement_type: ''
  };
  console.log('Achievement reset.');
};

const resetTestimonials = () => {
  testimonials.value = {
    graduate_testimonials_name: '',
    graduate_testimonials_role_title: '',
    graduate_testimonials_relationship: '',
    graduate_testimonials_testimonial: ''
  };
  console.log('Testimonials reset.');
};

const resetEmploymentPreferences = () => {
  employmentPreferences.value = {
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
  };
  console.log('Employment preferences reset.');
};

const resetCareerGoals = () => {
  careerGoals.value = {
    shortTermGoals: '',
    longTermGoals: '',
    industriesOfInterest: [],
    careerPath: ''
  };
  console.log('Career goals reset.');
};

const resetResume = () => {
  resume.value = {
    file: null,
    fileName: ''
  };
  console.log('Resume reset.');
};

// Modal Control Functions
const closeProfileModal = () => {
  isProfileModalOpen.value = false;
  console.log('Profile modal closed.');
};

const closeNoChangesModal = () => {
  isNoChangesModalOpen.value = false;
  console.log('No changes modal closed.');
};

const closePasswordModal = () => {
  isPasswordModalOpen.value = false;
  console.log('Password modal closed.');
};

const closeAddEducationModal = () => {
  isAddEducationModalOpen.value = false;
  resetEducation();
  console.log('Add education modal closed.');
};

const closeUpdateEducationModal = () => {
  isUpdateEducationModalOpen.value = false;
  education.value = {
    graduate_education_institution_id: '',
    graduate_education_program: '',
    graduate_education_field_of_study: '',
    graduate_education_start_date: null,
    graduate_education_end_date: null,
    graduate_education_description: ''
  };
  console.log('Update education modal closed.');
  resetEducation();
};

const closeEducationAddedModal = () => {
  isEducationAddedModalOpen.value = false;
  console.log('Education added modal closed.');
};

const closeEducationUpdatedModal = () => {
  isEducationUpdatedModalOpen.value = false;
  console.log('Education updated modal closed.');
};

const closeSkillsAddedModal = () => {
  isSkillsAddedModalOpen.value = false;
  console.log('Skills added modal closed.');
};

const closeUpdateSkillModal = () => {
  isUpdateSkillModalOpen.value = false;
  skillName.value = '';
  skillProficiencyType.value = '';
  skillType.value = '';
  yearsExperience.value = 0;
  console.log('Update skill modal closed.');
};


const closeAddExperienceModal = () => {
  isAddExperienceModalOpen.value = false;
  console.log('Add experience modal closed.');
};

const closeUpdateExperienceModal = () => {
  isUpdateExperienceModalOpen.value = false;
  resetExperience();
  console.log('Update experience modal closed.');
};

const closeAddCertificationModal = () => {
  isAddCertificationModalOpen.value = false;
  resetCertification();
  noExpiryDate.value = false;
  console.log('Add certification modal closed.');
};

const closeAddAchievementModal = () => {
  isAddAchievementModalOpen.value = false;
  resetAchievement();
  console.log('Add achievement modal closed.');
};

const closeUpdateAchievementModal = () => {
  isUpdateAchievementModalOpen.value = false;
  resetAchievement();
  console.log('Update achievement modal closed.');
};

const closeAddTestimonialsModal = () => {
  isAddTestimonialsModalOpen.value = false;
  resetTestimonials();
  console.log('Add testimonials modal closed.');
};

const closeUpdateTestimonialsModal = () => {
  isUpdateTestimonialsModalOpen.value = false;
  resetTestimonials();
  console.log('Update testimonials modal closed.');
};

const closeAddLocationModal = () => {
  isAddLocationModalOpen.value = false;
  newLocation.value = '';
  console.log('Add location modal closed.');
};

const onFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      profile.value.graduate_picture_url = e.target.result;
      console.log('Profile picture updated:', file.name);
    };
    reader.readAsDataURL(file);
  }
};

//  Grouping and Filtering Functions
// Computed property to group skills by type
const groupedSkills = computed(() => {
  if (!Array.isArray(skills.value)) {
    console.error('skills.value is not an array:', skills.value);
    return {};
  }
  return skills.value.reduce((acc, skill) => {
    const type = skill.graduate_skills_type || 'Unknown';
    if (!acc[type]) {
      acc[type] = [];
    }
    acc[type].push(skill);
    return acc;
  }, {});
});

// Computed property to filter skills based on selected type
const filteredSkills = computed(() => {
  if (!skillType.value) return skills.value;
  return skills.value.filter(skill => skill.graduate_skills_type === skillType.value);
});

const toggleJobType = (type) => {
  const index = employmentPreferences.value.jobTypes.indexOf(type);
  if (index === -1) {
    console.log('Job type added:', type);
    employmentPreferences.value.jobTypes.push(type);
  } else {
    employmentPreferences.value.jobTypes.splice(index, 1);
    console.log('Job type removed:', type);
  }
};

// Modal Opening Functions
const openAddSkillModal = () => {
  isAddSkillModalOpen.value = true;
  console.log('Add skill modal opened.');
};

const openAddIndustryModal = () => {
  isAddIndustryModalOpen.value = true;
  console.log('Add industry modal opened.');
};

const openUpdateEducationModal = (educationData) => {
  education.value = {
    ...educationData,
    noAchievements: !educationData.achievements, 
  };
  isUpdateEducationModalOpen.value = true;
  console.log('Update education modal opened with data:', educationData);
};

const openUpdateExperienceModal = (entry, index) => {
  experience.value = { ...entry };
  currentExperienceIndex.value = index;
  isUpdateExperienceModalOpen.value = true;
  console.log('Update experience modal opened with entry:', entry);
};

const openUpdateAchievementModal = (entry, index) => {
  achievement.value = { ...entry };
  currentAchievementIndex.value = index;
  isUpdateAchievementModalOpen.value = true;
  console.log('Update achievement modal opened with entry:', entry);
};

const openUpdateCertificationModal = (entry) => {
  // Populate the form with the selected entry data
  form.graduate_certification_name = entry.graduate_certification_name;
  form.graduate_certification_issuer = entry.graduate_certification_issuer;
  form.graduate_certification_issue_date = entry.graduate_certification_issue_date;
  form.graduate_certification_expiry_date = entry.graduate_certification_expiry_date;
  form.graduate_certification_credential_url = entry.graduate_certification_credential_url;
  form.avatar = null; // Reset file input
  certification.value = { ...entry };
  isUpdateCertificationModalOpen.value = true;
  console.log('Update certification modal opened with entry:', entry);
};

const openUpdateTestimonialsModal = (entry, index) => {
  testimonials.value = { ...entry };
  currentTestimonialsIndex.value = index;
  isUpdateTestimonialsModalOpen.value = true;
  console.log('Update testimonials modal opened with entry:', entry);
};

//  Confirmation and Removal Functions
const removeEducation = (id) => {
  if (confirm('Are you sure you want to remove this education entry?')) {
    educationEntries.value = educationEntries.value.filter(entry => entry.id !== id);
    console.log('Education entry removed from frontend.');
    
    alert('Education entry has been removed from your profile view.');
  }
};

const removeExperience = (id) => {
  if (confirm('Are you sure you want to remove this experience entry?')) {
    experienceEntries.value = experienceEntries.value.filter(entry => entry.id !== id);
    console.log('Experience entry removed from frontend.');
    
    alert('Experience entry has been removed from your profile view.');
  }
};

const removeSkill = (skill) => {
  if (confirm(`Are you sure you want to remove the skill: ${skill.graduate_skills_name}?`)) {
    skills.value = skills.value.filter(s => s.id !== skill.id);
    console.log(`Skill "${skill.graduate_skills_name}" removed from frontend.`);

    alert(`Skill "${skill.graduate_skills_name}" has been removed from your profile view.`);
  }
};

const removeTestimonials = (id) => {
  if (confirm('Are you sure you want to remove this testimonial entry?')) {
    testimonialsEntries.value = testimonialsEntries.value.filter(entry => entry.id !== id);
    console.log('Testimonial entry removed from frontend.');
    
    alert('Testimonial entry has been removed from your profile view.');
  }
};

const removeCertification = (id) => {
  if (confirm('Are you sure you want to remove this certification entry?')) {
    certificationsEntries.value = certificationsEntries.value.filter(entry => entry.id !== id);
    console.log('Certification entry removed from frontend.');
    
    alert('Certification entry has been removed from your profile view.');
  }
};

const removeAchievement = (id) => {
  if (confirm('Are you sure you want to remove this achievement entry?')) {
    achievementEntries.value = achievementEntries.value.filter(entry => entry.id !== id);
    console.log('Achievement entry removed from frontend.');
    
    alert('Achievement entry has been removed from your profile view.');
  }
};

const deleteProject = (index) => {
  if (confirm('Are you sure you want to remove this project entry?')) {
    projectsEntries.value.splice(index, 1);
    console.log('Project entry removed from frontend.');
    
    alert('Project entry has been removed from your profile view.');
  }
};

const closeAllModals = () => {
  closeProfileModal();
 closeNoChangesModal();
  closePasswordModal();
  closeAddEducationModal();
  closeUpdateEducationModal();
  closeSkillsAddedModal();
  closeAddExperienceModal();
  closeUpdateExperienceModal();
  closeAddCertificationModal();
  closeAddAchievementModal();
  closeUpdateAchievementModal();
  closeAddTestimonialsModal();
  closeUpdateTestimonialsModal();
  closeAddLocationModal();
  closeAddIndustryModal();
  closeUpdateCertificationModal();
};

// Additional cleanup for resetting states
const resetAllStates = () => {
  resetEducation();
  resetExperience();
  resetAchievement();
  resetCertification();
  resetTestimonials();
  resetCareerGoals();
  resetEmploymentPreferences();
  console.log('All states reset.');
};

// Event listeners for file uploads
const handleFileUpload = (event) => {
  form.avatar = event.target.files[0];
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      profile.value.graduate_picture_url = e.target.result;
      console.log('Profile picture uploaded:', file.name);
    };
    reader.readAsDataURL(file);
  }
};

// Function to initialize data on component mount
const initializeData = () => {
  // Fetch initial data or set defaults
  console.log('Initializing data...');
};

// Call initialize function on component mount
onMounted(() => {
  initializeData();
});

</script>

<template>
  <Graduate>
    <div class="bg-gray-100 min-h-screen p-6">
      <div class="container mx-auto">
        <h1 class="text-3xl font-bold mb-4">Profile Settings</h1>
        <p class="text-gray-600 mb-6">Manage your personal information and account settings</p>
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex border-b border-gray-200 mb-6">
            <button class="py-2 px-4"
              :class="{'text-indigo-600 border-b-2 border-indigo-600': activeSection === 'general'}"
              @click="setActiveSection('general')">
              General
            </button>

            <button class="py-2 px-4"
              :class="{'text-indigo-600 border-b-2 border-indigo-600': activeSection === 'security'}"
              @click="setActiveSection('security')">
              Credentials
            </button>

            <button class="py-2 px-4"
              :class="{'text-indigo-600 border-b-2 border-indigo-600': activeSection === 'education'}"
              @click="setActiveSection('education')">
              Education
            </button>

            <button class="py-2 px-4"
              :class="{'text-indigo-600 border-b-2 border-indigo-600': activeSection === 'skills'}"
              @click="setActiveSection('skills')">
              Skills
            </button>

            <button class="py-2 px-4"
              :class="{'text-indigo-600 border-b-2 border-indigo-600': activeSection === 'experience'}"
              @click="setActiveSection('experience')">
              Experience
            </button>

            <button class="py-2 px-4"
              :class="{'text-indigo-600 border-b-2 border-indigo-600': activeSection === 'projects'}"
              @click="setActiveSection('projects')">
              Projects
            </button>

            <button class="py-2 px-4"
              :class="{'text-indigo-600 border-b-2 border-indigo-600': activeSection === 'certifications'}"
              @click="setActiveSection('certifications')">
              Certifications
            </button>

            <button class="py-2 px-4"
              :class="{'text-indigo-600 border-b-2 border-indigo-600': activeSection === 'achievements'}"
              @click="setActiveSection('achievements')">
              Achievements
            </button>

            <button class="py-2 px-4"
              :class="{'text-indigo-600 border-b-2 border-indigo-600': activeSection === 'testimonials'}"
              @click="setActiveSection('testimonials')">
              Testimonials
            </button>

            <button class="py-2 px-4"
              :class="{'text-indigo-600 border-b-2 border-indigo-600': activeSection === 'employment'}"
              @click="setActiveSection('employment')">
              Employment
            </button>

            <button class="py-2 px-4"
              :class="{'text-indigo-600 border-b-2 border-indigo-600': activeSection === 'career-goals'}"
              @click="setActiveSection('career-goals')">
              Career Goals
            </button>

            <button class="py-2 px-4"
              :class="{'text-indigo-600 border-b-2 border-indigo-600': activeSection === 'resume'}"
              @click="setActiveSection('resume')">
              Resume
            </button>
          </div>

          <!-- General Section -->
          <div v-if="activeSection === 'general'" class="flex flex-col lg:flex-row">
            <div class="w-full lg:w-1/3 mb-6 lg:mb-0">
              <h2 class="text-xl font-semibold mb-4">Profile Picture</h2>
              <p class="text-gray-600 mb-4">Update your profile picture</p>
              <div class="flex flex-col items-center">
                <img id="profile-picture" :src="profile.graduate_picture_url" alt="Profile picture"
                  class="rounded-full mb-4 w-32 h-32 object-cover" />
                <input type="file" id="file-input" class="hidden" accept="image/*" @change="onFileChange" />
                <label for="file-input" class="text-indigo-600 cursor-pointer">Choose an image</label>
              </div>
            </div>

            <div class="w-full lg:w-2/3">
              <h2 class="text-xl font-semibold mb-4">Profile Information</h2>
              <p class="text-gray-600 mb-4">Update your personal information</p>
              <form @submit.prevent="saveProfile">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-4">
                  <div class="relative">
                    <label for="full-name" class="block text-gray-700 mb-1">Full Name</label>
                    <i class="fas fa-user absolute left-3 top-10 text-gray-400"></i>
                    <input type="text" id="full-name"
                      class="w-full border border-gray-300 rounded-md p-2 pl-10 outline-none focus:ring-2 focus:ring-indigo-600"
                      v-model="profile.fullName" placeholder="Enter your full name" />
                  </div>

                  <div class="relative">
                    <label for="professional-title" class="block text-gray-700 mb-1">Professional Title</label>
                    <i class="fas fa-briefcase absolute left-3 top-10 text-gray-400"></i>
                    <input type="text" id="graduate_professional-title"
                      class="w-full border border-gray-300 rounded-md p-2 pl-10 outline-none focus:ring-2 focus:ring-indigo-600"
                      v-model="profile.graduate_professional_title" placeholder="Enter your professional title" />
                  </div>

                  <div class="relative">
                    <label for="email-address" class="block text-gray-700 mb-1">Email Address</label>
                    <i class="fas fa-envelope absolute left-3 top-10 text-gray-400"></i>
                    <input type="email" id="email-address"
                      class="w-full border border-gray-300 rounded-md p-2 pl-10 outline-none focus:ring-2 focus:ring-indigo-600"
                      v-model="profile.email" placeholder="Enter your email address" />
                  </div>

                  <div class="relative">
                    <label for="phone" class="block text-gray-700 mb-1">Phone Number</label>
                    <i class="fas fa-phone absolute left-3 top-10 text-gray-400"></i>
                    <input type="text" id="phone"
                      class="w-full border border-gray-300 rounded-md p-2 pl-10 outline-none focus:ring-2 focus:ring-indigo-600"
                      v-model="profile.graduate_phone" placeholder="Enter your phone number" />
                  </div>

                  <div class="relative">
                    <label for="location" class="block text-gray-700 mb-1">Location</label>
                    <i class="fas fa-map-marker-alt absolute left-3 top-10 text-gray-400"></i>
                    <input type="text" id="location"
                      class="w-full border border-gray-300 rounded-md p-2 pl-10 outline-none focus:ring-2 focus:ring-indigo-600"
                      v-model="profile.graduate_location" placeholder="Enter your location" />
                  </div>

                  <div class="relative">
                    <label for="birthdate" class="block text-gray-700 mb-1">Birthdate</label>
                    <i class="fas fa-calendar-alt absolute left-3 top-10 text-gray-400"></i>
                    <datepicker v-model="profile.graduate_birthdate" :config="datepickerConfig"
                      class="w-full border border-gray-300 rounded-md p-2 pl-10 outline-none focus:ring-2 focus:ring-indigo-600"
                      placeholder="Select your birthdate" />
                  </div>

                  <div class="relative">
                    <label for="gender" class="block text-gray-700 mb-1">Gender</label>
                    <i class="fas fa-venus-mars absolute left-3 top-10 text-gray-400"></i>
                    <select id="gender"
                      class="w-full border border-gray-300 rounded-md p-2 pl-10 outline-none focus:ring-2 focus:ring-indigo-600"
                      v-model="profile.graduate_gender"> // Updated field name
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
                    <input type="text" id="ethnicity"
                      class="w-full border border-gray-300 rounded-md p-2 pl-10 outline-none focus:ring-2 focus:ring-indigo-600"
                      v-model="profile.graduate_ethnicity" placeholder="Enter your ethnicity" />
                  </div>
                </div>

                <div class="mb-4 relative">
                  <label for="address" class="block text-gray-700 mb-1">Home Address</label>
                  <i class="fas fa-home absolute left-3 top-10 text-gray-400"></i>
                  <input type="text" id="address"
                    class="w-full border border-gray-300 rounded-md p-2 pl-10 outline-none focus:ring-2 focus:ring-indigo-600"
                    v-model="profile.graduate_address" placeholder="Enter your address" />
                </div>

                <div class="mb-4 relative">
                  <label for="about-me" class="block text-gray-700 mb-1">About Me</label>
                  <i class="fas fa-info-circle absolute left-3 top-10 text-gray-400"></i>
                  <textarea id="about-me"
                    class="w-full border border-gray-300 rounded-md p-2 pl-10 outline-none focus:ring-2 focus:ring-indigo-600"
                    rows="4" v-model="profile.graduate_about_me" placeholder="Tell us about yourself"></textarea>
                </div>

                <button class="bg-indigo-600 text-white py-2 px-4 rounded-md">
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
                  <input type="password" id="current-password" v-model="settingsForm.current_password"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                    placeholder="Current Password" />
                </div>
                <div class="mb-4">
                  <label class="block text-gray-700 mb-2" for="new-password">New Password</label>
                  <input type="password" id="new-password" v-model="settingsForm.password"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                    placeholder="New Password" />
                </div>
                <div class="mb-6">
                  <label class="block text-gray-700 mb-2" for="confirm-password">Confirm New Password</label>
                  <input type="password" id="confirm-password" v-model="settingsForm.password_confirmation"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                    placeholder="Confirm New Password" />
                </div>
                <button type="submit"
                  class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-600">
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
                <button class="bg-indigo-600 text-white px-4 py-2 rounded flex items-center"
                  @click="isAddEducationModalOpen = true">
                  <i class="fas fa-plus mr-2"></i>
                  Add Education
                </button>
              </div>
              <p class="text-gray-600 mb-6">Manage your educational background</p>
              <div v-if="educationEntries.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div v-for="entry in educationEntries" :key="entry.id" class="bg-white p-8 rounded-lg shadow relative">
                  <div>
                    <h2 class="text-xl font-bold">{{ entry.graduate_education_institution_id }}</h2>
                    <p class="text-gray-600">
                      {{ entry.graduate_education_program }} in {{ entry.graduate_education_field_of_study }}
                    </p>
                    <div class="flex items-center text-gray-600 mt-2">
                      <i class="far fa-calendar-alt mr-2"></i>
                      <span>
                        {{ formatDate(entry.graduate_education_start_date) }} - {{ entry.graduate_education_end_date ?
                        formatDate(entry.graduate_education_end_date) : 'present' }}
                      </span>
                    </div>
                    <p class="mt-2">
                      <strong>
                        <i class="fas fa-info-circle text-gray-500 mr-2"></i> Description:
                      </strong>
                      {{ entry.graduate_education_description || 'No description provided' }}
                    </p>
                    <p class="mt-2">
                      <strong>
                        <i class="fas fa-trophy text-gray-500 mr-2"></i> Achievements:
                      </strong>
                      <span v-if="entry.achievements && entry.achievements.includes(',')">
                        <ul class="list-disc list-inside">
                          <li v-for="(achievement, index) in entry.achievements.split(',')" :key="index">
                            {{ achievement.trim() }}
                          </li>
                        </ul>
                      </span>
                      <span v-else>
                        {{ entry.achievements || 'None' }}
                      </span>
                    </p>
                  </div>
                  <div class="absolute top-8 right-4 flex space-x-4">
                    <button class="text-gray-600 hover:text-indigo-600" @click="openUpdateEducationModal(entry)">
                      <i class="fas fa-pen"></i>
                    </button>
                    <button class="text-red-600 hover:text-red-800" @click="removeEducation(entry.id)">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </div>
              </div>

              <!-- If no education entries exist -->
              <div v-else class="bg-white p-8 rounded-lg shadow">
                <p class="text-gray-600">No education entries added yet.</p>
              </div>
            </div>


            <!-- Add Education Modal -->
            <div v-if="isAddEducationModalOpen"
              class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
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
                      <input type="text" v-model="education.graduate_education_institution_id"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="e.g. Harvard University" required>
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Degree</label>
                      <input type="text" v-model="education.graduate_education_program"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="e.g. Bachelor of Science" required>
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Field of Study</label>
                      <input type="text" v-model="education.graduate_education_field_of_study"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="e.g. Computer Science" required>
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Start Date</label>
                      <Datepicker v-model="education.graduate_education_start_date"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="Select start date" required />
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">End Date</label>
                      <Datepicker v-model="education.graduate_education_end_date"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="Select end date" :disabled="education.is_current" />
                      <div class="mt-2">
                        <input type="checkbox" id="is-current" v-model="education.is_current" />
                        <label for="is-current" class="text-sm text-gray-700 ml-2">I currently study here</label>
                      </div>
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Description</label>
                      <textarea v-model="education.graduate_education_description"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        rows="3" placeholder="Describe your experience..."></textarea>
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Achievements</label>
                      <textarea v-model="education.achievements"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        rows="3" placeholder="List honors, awards, and scholarships..."
                        :disabled="education.noAchievements"></textarea>
                      <div class="mt-2">
                        <input type="checkbox" id="no-achievements" v-model="education.noAchievements"
                          @change="handleNoAchievements" />
                        <label for="no-achievements" class="text-sm text-gray-700 ml-2">No Achievements</label>
                      </div>
                    </div>
                    <div class="flex justify-end">
                      <button type="submit"
                        class="w-full bg-indigo-600 text-white py-2 rounded-md flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>
                        Add Education
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- Update Education Modal -->
            <div v-if="isUpdateEducationModalOpen"
              class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
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
                      <input type="text" v-model="education.graduate_education_institution_id"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="e.g. Harvard University">
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Degree</label>
                      <input type="text" v-model="education.graduate_education_program"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="e.g. Bachelor of Science">
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Field of Study</label>
                      <input type="text" v-model="education.graduate_education_field_of_study"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="e.g. Computer Science">
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Start Date</label>
                      <Datepicker v-model="education.graduate_education_start_date" :config="datepickerConfig"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="Select start date" />
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">End Date</label>
                      <Datepicker v-model="education.graduate_education_end_date"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="Select end date" :disabled="education.is_current" />
                      <div class="mt-2">
                        <input type="checkbox" id="is-current-update" v-model="education.is_current"
                          @change="handleIsCurrent" />
                        <label for="is-current-update" class="text-sm text-gray-700 ml-2">I currently study here</label>
                      </div>
                    </div>

                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Description</label>
                      <textarea v-model="education.graduate_education_description"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        rows="3" placeholder="Describe your experience..."></textarea>
                    </div>

                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Achievements</label>
                      <textarea v-model="education.achievements"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        rows="3" placeholder="List honors, awards, and scholarships..."
                        :disabled="education.noAchievements"></textarea>
                      <div class="mt-2">
                        <input type="checkbox" id="no-achievements" v-model="education.noAchievements"
                          @change="handleNoAchievements" />
                        <label for="no-achievements" class="text-sm text-gray-700 ml-2">No Achievements</label>
                      </div>
                    </div>

                    <div class="flex justify-end">
                      <button type="submit"
                        class="w-full bg-indigo-600 text-white py-2 rounded-md flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>
                        Update Education
                      </button>
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
                <button class="bg-indigo-600 text-white px-4 py-2 rounded flex items-center" @click="openAddSkillModal">
                  <i class="fas fa-plus mr-2"></i>
                  Add Skill
                </button>
              </div>
              <p class="text-gray-600 mb-6">Showcase your skills</p>
              <div v-if="Object.keys(groupedSkills).length > 0" class="space-y-6">
                <div v-for="(skillsGroup, type) in groupedSkills" :key="type"
                  class="bg-white p-8 rounded-lg shadow relative">
                  <h2 class="text-xl font-bold text-indigo-600 mb-4">{{ type }}</h2>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div v-for="skill in skillsGroup" :key="skill.graduate_skills_name"
                      class="bg-gray-50 p-8 rounded-lg shadow relative">
                      <!-- Edit and Delete Buttons -->
                      <div class="absolute top-8 right-4 flex space-x-4">
                        <button class="text-gray-600 hover:text-indigo-600" @click="editSkill(skill)">
                          <i class="fas fa-pen"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-800" @click="removeSkill(skill)">
                          <i class="fas fa-trash"></i>
                        </button>
                      </div>
                      <div>
                        <h3 class="text-lg font-bold text-gray-800">{{ skill.graduate_skills_name }}</h3>
                        <p class="text-gray-600 mt-2 flex items-center">
                          <i class="fas fa-chart-line text-gray-600 mr-2"></i>Proficiency: {{
                          skill.graduate_skills_proficiency_type }}
                        </p>
                        <p class="text-gray-600 mt-2 flex items-center">
                          <i class="fas fa-clock text-gray-600 mr-2"></i>Years of Experience: {{
                          skill.graduate_skills_years_experience }} year/s
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- No Skills Message -->
              <div v-else class="bg-white p-8 rounded-lg shadow mb-4">
                <p class="text-gray-600">No skills entries added yet.</p>
              </div>
            </div>

            <!-- Add Skills Modal -->
            <div v-if="isAddSkillModalOpen"
              class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
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
                    <input type="text" id="skillName" v-model="skillName"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                  </div>
                  <div class="mb-4">
                    <label for="skillProficiencyType" class="block text-gray-700">Proficiency Type</label>
                    <select id="skillProficiencyType" v-model="skillProficiencyType"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                      <option value="" disabled>Select proficiency type</option>
                      <option value="Beginner">Beginner</option>
                      <option value="Intermediate">Intermediate</option>
                      <option value="Advanced">Advanced</option>
                      <option value="Expert">Expert</option>
                    </select>
                  </div>
                  <div class="mb-4">
                    <label for="skillType" class="block text-gray-700">Skill Type</label>
                    <select id="skillType" v-model="skillType"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
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
                    <input type="number" id="yearsExperience" v-model="yearsExperience"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                  </div>
                  <button type="submit"
                    class="w-full bg-indigo-600 text-white py-2 rounded-md flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i>
                    Save Skill
                  </button>
                </form>
              </div>
            </div>

            <!-- Update Skills Modal -->
            <div v-if="isUpdateSkillModalOpen"
              class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
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
                    <input type="text" id="skillName" v-model="skillName"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                  </div>
                  <div class="mb-4">
                    <label for="skillProficiencyType" class="block text-gray-700">Proficiency Type</label>
                    <select id="skillProficiencyType" v-model="skillProficiencyType"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                      <option value="" disabled>Select proficiency type</option>
                      <option value="Beginner">Beginner</option>
                      <option value="Intermediate">Intermediate</option>
                      <option value="Advanced">Advanced</option>
                      <option value="Expert">Expert</option>
                    </select>
                  </div>
                  <div class="mb-4">
                    <label for="skillType" class="block text-gray-700">Skill Type</label>
                    <select id="skillType" v-model="skillType"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
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
                    <input type="number" id="yearsExperience" v-model="yearsExperience"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                  </div>
                  <button type="submit"
                    class="w-full bg-indigo-600 text-white py-2 rounded-md flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i>
                    Update Skill
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
                <button class="bg-indigo-600 text-white px-4 py-2 rounded flex items-center"
                  @click="isAddExperienceModalOpen = true">
                  <i class="fas fa-plus mr-2"></i> Add Experience
                </button>
              </div>
              <p class="text-gray-600 mb-6">Showcase your professional experience</p>
              <div v-if="experienceEntries.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div v-for="(entry, index) in experienceEntries" :key="entry.id"
                  class="bg-white p-8 rounded-lg shadow relative">
                  <div>
                    <h2 class="text-xl font-bold">{{ entry.graduate_experience_title }}</h2>
                    <p class="text-gray-600">{{ entry.graduate_experience_company }}</p>
                    <div class="flex items-center text-gray-600 mt-2">
                      <i class="fas fa-map-marker-alt mr-2"></i>
                      <span>{{ entry.graduate_experience_address }}</span>
                    </div>
                    <div class="flex items-center text-gray-600 mt-2">
                      <i class="far fa-calendar-alt mr-2"></i>
                      <span>
                        {{ formatDate(entry.graduate_experience_start_date) }} -
                        {{ entry.graduate_experience_end_date ? formatDate(entry.graduate_experience_end_date) :
                        'Present' }}
                      </span>
                    </div>
                    <p class="text-gray-600 mt-2 flex items-center">
                      <i class="fas fa-briefcase text-gray-500 mr-2"></i>
                      <strong>Employment Type:</strong> {{ entry.graduate_experience_employment_type }}
                    </p>
                    <p class="mt-2">
                      <strong>
                        <i class="fas fa-info-circle text-gray-500 mr-2"></i> Description:
                      </strong>
                      {{ entry.graduate_experience_description || 'No description provided' }}
                    </p>
                  </div>
                  <div class="absolute top-2 right-2 flex space-x-2">
                    <button class="text-gray-600 hover:text-indigo-600"
                      @click="openUpdateExperienceModal(entry, index)">
                      <i class="fas fa-pen"></i>
                    </button>
                    <button class="text-red-600 hover:text-red-800" @click="removeExperience(entry.id)">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </div>
              </div>

              <!-- If no experience entries exist -->
              <div v-else class="bg-white p-8 rounded-lg shadow">
                <p class="text-gray-600">No experience entries added yet.</p>
              </div>
            </div>

            <!-- Add Experience Modal -->
            <div v-if="isAddExperienceModalOpen"
              class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
              <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                  <h2 class="text-xl font-semibold">Add Experience</h2>
                  <button class="text-gray-500 hover:text-gray-700" @click="closeAddExperienceModal">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
                <div class="max-h-96 overflow-y-auto">
                  <form @submit.prevent="addExperience">
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Job Title</label>
                      <input type="text" v-model="experience.graduate_experience_title"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="e.g. Software Engineer" required />
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Company</label>
                      <input type="text" v-model="experience.graduate_experience_company"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="e.g. Tech Corp" required />
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Location</label>
                      <input type="text" v-model="experience.graduate_experience_address"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="e.g. New York, NY" required />
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Employment Type</label>
                      <select v-model="experience.graduate_experience_employment_type"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        required>
                        <option value="" disabled>Select employment type</option>
                        <option value="Full-time">Full-time</option>
                        <option value="Part-time">Part-time</option>
                        <option value="Contract">Contract</option>
                        <option value="Freelance">Freelance</option>
                        <option value="Internship">Internship</option>
                      </select>
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Start Date</label>
                      <Datepicker v-model="experience.graduate_experience_start_date"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="Select start date" required />
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">End Date</label>
                      <Datepicker v-model="experience.graduate_experience_end_date"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="Select end date" :disabled="stillInRole" />
                      <div class="mt-2">
                        <input type="checkbox" id="still-in-role" v-model="stillInRole"
                          @change="experience.graduate_experience_end_date = stillInRole ? null : experience.graduate_experience_end_date" />
                        <label for="still-in-role" class="text-sm text-gray-700 ml-2">I currently work here</label>
                      </div>
                    </div>
                    <div class="flex justify-end">
                      <button type="submit"
                        class="w-full bg-indigo-600 text-white py-2 rounded-md flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>
                        Save Experience
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- UpdateExperience Modal -->
            <div v-if="isUpdateExperienceModalOpen"
              class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
              <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                  <h2 class="text-xl font-semibold">Update Experience</h2>
                  <button class="text-gray-500 hover:text-gray-700" @click="closeUpdateExperienceModal">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
                <div class="max-h-96 overflow-y-auto">
                  <form @submit.prevent="updateExperience">
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Job Title</label>
                      <input type="text" v-model="experience.graduate_experience_title"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="e.g. Software Engineer" required />
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Company</label>
                      <input type="text" v-model="experience.graduate_experience_company"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="e.g. Tech Corp" required />
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Location</label>
                      <input type="text" v-model="experience.graduate_experience_address"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="e.g. New York, NY" required />
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Employment Type</label>
                      <select v-model="experience.graduate_experience_employment_type"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        required>
                        <option value="" disabled>Select employment type</option>
                        <option value="Full-time">Full-time</option>
                        <option value="Part-time">Part-time</option>
                        <option value="Contract">Contract</option>
                        <option value="Freelance">Freelance</option>
                        <option value="Internship">Internship</option>
                      </select>
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Start Date</label>
                      <Datepicker v-model="experience.graduate_experience_start_date"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="Select start date" required />
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">End Date</label>
                      <Datepicker v-model="experience.graduate_experience_end_date"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="Select end date" :disabled="stillInRole" />
                      <div class="mt-2">
                        <input type="checkbox" id="still-in-role-update" v-model="stillInRole"
                          @change="experience.graduate_experience_end_date = stillInRole ? null : experience.graduate_experience_end_date" />
                        <label for="still-in-role-update" class="text-sm text-gray-700 ml-2">I currently work
                          here</label>
                      </div>
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Description</label>
                      <textarea v-model="experience.graduate_experience_description"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        rows="3" placeholder="Describe your role and responsibilities..."></textarea>
                    </div>
                    <div class="flex justify-end">
                      <button type="submit"
                        class="w-full bg-indigo-600 text-white py-2 rounded-md flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>
                        Update
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <!-- Project Section -->
          <div v-if="activeSection === 'projects'" class="flex flex-col lg:flex-row">
            <div class="w-full lg:w-full mb-6 lg:mb-0">
              <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-semibold">Projects</h1>
                <button class="bg-indigo-600 text-white px-4 py-2 rounded flex items-center hover:bg-indigo-700"
                  @click="isAddProjectModalOpen = true">
                  <i class="fas fa-plus mr-2"></i> Add Project
                </button>
              </div>
              <p class="text-gray-600 mb-6">Showcase your personal and professional projects</p>

              <div v-if="projectsEntries.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div v-for="(entry, index) in projectsEntries" :key="entry.id"
                  class="bg-white p-8 rounded-lg shadow relative">
                  <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex justify-between items-center mb-4">
                      <h2 class="text-xl font-bold">{{ entry.graduate_projects_title }}</h2>
                      <div class="flex space-x-2">
                        <button class="text-gray-600 hover:text-gray-800" @click="openUpdateProjectModal(entry)">
                          <i class="fas fa-pen"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-800" @click="deleteProject(index)">
                          <i class="fas fa-trash"></i>
                        </button>
                      </div>
                    </div>
                    <p class="text-gray-600 mb-4">{{ entry.graduate_projects_description || 'No description provided' }}
                    </p>
                    <div class="flex justify-between items-center mb-4">
                      <p class="text-xl font-bold">{{ entry.graduate_projects_role }}</p>
                    </div>
                    <div class="flex items-center text-gray-600 mt-2">
                      <i class="far fa-calendar-alt mr-2"></i>
                      <span>{{ formatDate(entry.graduate_projects_start_date) }} - {{ entry.graduate_projects_end_date ?
                        formatDate(entry.graduate_projects_end_date) : 'Present' }}</span>
                    </div>
                    <p class="text-gray-600 mt-2">
                      <strong>Project URL:</strong>
                      <span v-if="entry.graduate_projects_url">
                        <a :href="entry.graduate_projects_url" target="_blank"
                          class="text-indigo-600 hover:underline">{{ entry.graduate_projects_url }}</a>
                      </span>
                      <span v-else>No URL provided</span>
                    </p>
                    <div class="flex flex-wrap gap-2 mb-4">
                      <span v-for="(tech, techIndex) in entry.graduate_projects_tech" :key="techIndex"
                        class="bg-indigo-600 text-white px-2 py-1 rounded-full text-sm mr-2">
                        {{ tech }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <div v-else class="bg-white p-8 rounded-lg shadow">
                <p class="text-gray-600">No project entries added yet.</p>
              </div>

              <!-- Add Project Modal -->
              <div v-if="isAddProjectModalOpen"
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                  <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Add Project</h2>
                    <button class="text-gray-500 hover:text-gray-700" @click="closeAddProjectModal">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                  <div class="max-h-96 overflow-y-auto">
                    <form @submit.prevent="addProject">
                      <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Project Title</label>
                        <input type="text" v-model="projects.graduate_projects_title"
                          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                          placeholder="e.g. E-commerce Platform" required />
                      </div>
                      <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Description</label>
                        <textarea v-model="projects.graduate_projects_description"
                          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                          rows="3" placeholder="Describe your personal or professional project..."></textarea>
                      </div>
                      <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Role</label>
                        <input type="text" v-model="projects.graduate_projects_role"
                          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                          placeholder="Your role in the project" />
                      </div>
                      <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Start Date</label>
                        <Datepicker v-model="projects.graduate_projects_start_date"
                          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                          placeholder="Select start date" required />
                      </div>
                      <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">End Date</label>
                        <Datepicker v-model="projects.graduate_projects_end_date"
                          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                          placeholder="Select end date" :disabled="projects.is_current" />
                        <div class="mt-2">
                          <input type="checkbox" v-model="projects.is_current" id="isCurrentProject" />
                          <label for="isCurrentProject" class="text-sm text-gray-700 ml-2">This is an ongoing
                            project</label>
                        </div>
                      </div>
                      <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Project URL</label>
                        <input type="url" v-model="projects.graduate_projects_url"
                          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                          placeholder="e.g. https://yourproject.com" :disabled="noProjectUrl" />
                      </div>
                      <div class="mb-4">
                        <input type="checkbox" v-model="noProjectUrl" id="noProjectUrl" />
                        <label for="noProjectUrl" class="text-gray-700 font-medium ml-2">No Project URL</label>
                      </div>
                      <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Technologies Used</label>
                        <div class="flex items-center gap-2">
                          <input type="text" v-model="newTech"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                            placeholder="Enter a skill and press Add" />
                          <button type="button"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700"
                            @click="addTechToProject">
                            Add
                          </button>
                        </div>
                        <div class="flex flex-wrap gap-2 mt-2">
                          <span v-for="(tech, index) in projects.graduate_projects_tech" :key="index"
                            class="bg-gray-200 text-gray-700 px-4 py-2 rounded-full flex items-center">
                            {{ tech }}
                            <button type="button" class="ml-2 text-red-600 hover:text-red-800"
                              @click="removeTechFromProject(index)">
                              <i class="fas fa-times"></i>
                            </button>
                          </span>
                        </div>
                      </div>
                      <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Key Accomplishments</label>
                        <textarea v-model="projects.graduate_projects_key_accomplishments"
                          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                          rows="3" placeholder="What did you achieve?"></textarea>
                      </div>
                      <button type="submit"
                        class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">Add Project</button>
                    </form>
                  </div>
                </div>
              </div>

              <!-- Update Project Modal -->
              <div v-if="isUpdateProjectModalOpen"
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                  <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Update Project</h2>
                    <button class="text-gray-500 hover:text-gray-700" @click="closeUpdateProjectModal">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                  <div class="max-h-96 overflow-y-auto">
                    <form @submit.prevent="updateProject">
                      <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Project Title</label>
                        <input type="text" v-model="projects.graduate_projects_title"
                          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                          placeholder="e.g. E-commerce Platform" required />
                      </div>
                      <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Description</label>
                        <textarea v-model="projects.graduate_projects_description"
                          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                          rows="3" placeholder="Describe your personal or professional project..."></textarea>
                      </div>
                      <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Role</label>
                        <input type="text" v-model="projects.graduate_projects_role"
                          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                          placeholder="Your role in the project" />
                      </div>
                      <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Start Date</label>
                        <Datepicker v-model="projects.graduate_projects_start_date"
                          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                          placeholder="Start Date" required />
                      </div>
                      <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">End Date</label>
                        <Datepicker v-model="projects.graduate_projects_end_date"
                          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                          placeholder="End Date":disabled="projects.is_current" />
                        <div class="mt-2">
                          <input type="checkbox" v-model="projects.is_current" id="isCurrentProject" />
                          <label for="isCurrentProject" class="text-sm text-gray-700 ml-2">This is an ongoing
                            project</label>
                        </div>
                      </div>
                      <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Project URL</label>
                        <input type="url" v-model="projects.graduate_projects_url"
                          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                          placeholder="e.g. https://yourproject.com" :disabled="noProjectUrl" />
                      </div>
                      <div class="mb-4">
                        <input type="checkbox" v-model="noProjectUrl" id="noProjectUrl" />
                        <label for="noProjectUrl" class="text-gray-700 font-medium ml-2">No Project URL</label>
                      </div>
                      <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Technologies Used</label>
                        <div class="flex items-center gap-2">
                          <input type="text" v-model="newTech"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                            placeholder="Enter a skill and press Add" />
                          <button type="button"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700"
                            @click="addTechToProject">
                            Add
                          </button>
                        </div>
                        <div class="flex flex-wrap gap-2 mt-2">
                          <span v-for="(tech, index) in projects.graduate_projects_tech" :key="index"
                            class="bg-gray-200 text-gray-700 px-4 py-2 rounded-full flex items-center">
                            {{ tech }}
                            <button type="button" class="ml-2 text-red-600 hover:text-red-800"
                              @click="removeTechFromProject(index)">
                              <i class="fas fa-times"></i>
                            </button>
                          </span>
                        </div>
                      </div>
                      <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Key Accomplishments</label>
                        <textarea v-model="projects.graduate_projects_key_accomplishments"
                          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                          rows="3" placeholder="What did you achieve?"></textarea>
                      </div>
                      <button type="submit"
                        class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">Update
                        Project</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Certifications Section -->
          <div v-if="activeSection === 'certifications'" class="flex flex-col lg:flex-row">
            <div class="w-full lg:w-full mb-6 lg:mb-0">
              <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-semibold mb-4">Certifications</h1>
                <button class="bg-indigo-600 text-white px-4 py-2 rounded flex items-center hover:bg-indigo-700"
                  @click="isAddCertificationModalOpen = true">
                  <i class="fas fa-plus mr-2"></i> Add Certification
                </button>
              </div>
              <p class="text-gray-600 mb-6">Manage your professional certifications</p>
              <div v-if="certificationsEntries.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div v-for="entry in certificationsEntries" :key="entry.id"
                  class="bg-white p-8 rounded-lg shadow relative">
                  <div>
                    <h2 class="text-xl font-bold">{{ entry.graduate_certification_name }}</h2>
                    <p class="text-gray-600">{{ entry.graduate_certification_issuer }}</p>
                    <div class="flex items-center text-gray-600 mt-2">
                      <i class="far fa-calendar-alt mr-2"></i>
                      <span>{{ entry.graduate_certification_issue_date }} - {{ entry.graduate_certification_expiry_date
                        || 'No expiry date' }}</span>
                    </div>
                    <p class="mt-2">Credential ID: {{ entry.graduate_certification_credential_id }}</p>
                    <p class="mt-2">
                      URL: <a :href="entry.graduate_certification_credential_url" class="text-blue-600 hover:underline"
                        target="_blank">{{ entry.graduate_certification_credential_url }}</a>
                    </p>
                    <p class="mt-2" v-if="entry.file_path">
                      File: <a :href="`/storage/${entry.file_path}`" class="text-blue-600 hover:underline"
                        target="_blank">Download</a>
                    </p>
                  </div>
                  <div class="absolute top-2 right-2 flex space-x-2">
                    <button class="text-gray-600 hover:text-indigo-600" @click="openUpdateCertificationModal(entry)">
                      <i class="fas fa-pen"></i>
                    </button>
                    <button class="text-red-600 hover:text-red-800" @click="removeCertification(entry.id)">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </div>
              </div>

              <!-- If no certifications exist -->
              <div v-else class="bg-white p-8 rounded-lg shadow mb-4">
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
                    <input type="text" id="certification-name" v-model="certification.graduate_certification_name"
                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                      required>
                  </div>
                  <div class="mb-4">
                    <label for="certification-issuer" class="block text-sm font-medium text-gray-700">Issuer</label>
                    <input type="text" id="certification-issuer" v-model="certification.graduate_certification_issuer"
                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                      required>
                  </div>
                  <div class="mb-4">
                    <label for="certification-issue-date" class="block text-sm font-medium text-gray-700">Issue Date</label>
                    <Datepicker v-model="certification.graduate_certification_issue_date"
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                      placeholder="Select issue date" required>
                      <template #input-icon>
                        <i class="fas fa-calendar-alt"></i>
                      </template>
                    </Datepicker>
                  </div>
                  <div class="mb-4">
                    <label for="certification-expiry-date" class="block text-sm font-medium text-gray-700">Expiry Date</label>
                    <Datepicker v-model="certification.graduate_certification_expiry_date"
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                      placeholder="Select expiry date" :disabled="noExpiryDate" required>
                      <template #input-icon>
                        <i class="fas fa-calendar-alt"></i>
                      </template>
                    </Datepicker>
                    <div class="mt-2">
                      <input type="checkbox" id="no-expiry-date" v-model="noExpiryDate" class="mr-2" />
                      <label for="no-expiry-date" class="text-sm text-gray-700">No expiry date</label>
                    </div>
                  </div>
                  <div class="mb-4">
                    <label for="certification-credential-url" class="block text-sm font-medium text-gray-700">Credential URL</label>
                    <input type="url" id="certification-credential-url"
                      v-model="certification.graduate_certification_credential_url"
                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                      :disabled="noCredentialUrl" required>
                    <div class="mt-2">
                      <input type="checkbox" id="no-credential-url" v-model="noCredentialUrl" class="mr-2" />
                      <label for="no-credential-url" class="text-sm text-gray-700">No Credential URL</label>
                    </div>
                  </div>
                  <div class="mb-4">
                    <label for="certification-file" class="block text-sm font-medium text-gray-700">Upload File</label>
                    <input type="file" id="certification-file" @input="form.avatar = $event.target.files[0]"
                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                  </div>
                  <div class="flex justify-end">
                    <button type="submit"
                      class="w-full bg-indigo-600 text-white py-2 rounded-md flex items-center justify-center">
                      <i class="fas fa-save mr-2"></i>
                      Save Certification
                    </button>
                  </div>
                </form>
              </div>
            </div>

            <!-- Update Certification Modal -->
            <div v-if="isAddCertificationModalOpen"
              class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
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
                    <input type="text" id="certification-name" v-model="certification.graduate_certification_name"
                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                      required>
                  </div>
                  <div class="mb-4">
                    <label for="certification-issuer" class="block text-sm font-medium text-gray-700">Issuer</label>
                    <input type="text" id="certification-issuer" v-model="certification.graduate_certification_issuer"
                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                      required>
                  </div>
                  <div class="mb-4">
                    <label for="certification-issue-date" class="block text-sm font-medium text-gray-700">Issue
                      Date</label>
                    <Datepicker v-model="certification.graduate_certification_issue_date"
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                      placeholder="Select issue date" required />
                  </div>
                  <div class="mb-4">
                    <label for="certification-expiry-date" class="block text-sm font-medium text-gray-700">Expiry
                      Date</label>
                    <Datepicker v-model="certification.graduate_certification_expiry_date"
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                      placeholder="Select expiry date" :disabled="noExpiryDate" />
                    <div class="mt-2">
                      <input type="checkbox" id="no-expiry-date" v-model="noExpiryDate" class="mr-2" />
                      <label for="no-expiry-date" class="text-sm text-gray-700">No expiry date</label>
                    </div>
                  </div>
                  <div class="mb-4">
                    <label for="certification-credential-url" class="block text-sm font-medium text-gray-700">Credential
                      URL</label>
                    <input type="url" id="certification-credential-url"
                      v-model="certification.graduate_certification_credential_url"
                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                      :disabled="noCredentialUrl">
                    <div class="mt-2">
                      <input type="checkbox" id="no-credential-url" v-model="noCredentialUrl" class="mr-2" />
                      <label for="no-credential-url" class="text-sm text-gray-700">No Credential URL</label>
                    </div>
                  </div>
                  <div class="mb-4">
                    <label for="certification-file" class="block text-sm font-medium text-gray-700">Upload File</label>
                    <input type="file" id="certification-file" @input="form.avatar = $event.target.files[0]"
                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                  </div>
                  <div class="flex justify-end">
                    <button type="submit"
                      class="w-full bg-indigo-600 text-white py-2 rounded-md flex items-center justify-center">
                      <i class="fas fa-save mr-2"></i>
                      Save Certification
                    </button>
                  </div>
                </form>
              </div>
            </div>

            <!-- Update Certification Modal -->
            <div v-if="isUpdateCertificationModalOpen"
              class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
              <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                  <h2 class="text-xl font-semibold">Update Certification</h2>
                  <button class="text-gray-500 hover:text-gray-700" @click="closeAddedCertificationModal">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
                <form @submit.prevent="updateCertification">
                  <div class="mb-4">
                    <label for="certification-name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="certification-name" v-model="certification.graduate_certification_name"
                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                      required>
                  </div>
                  <div class="mb-4">
                    <label for="certification-issuer" class="block text-sm font-medium text-gray-700">Issuer</label>
                    <input type="text" id="certification-issuer" v-model="certification.graduate_certification_issuer"
                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                      required>
                  </div>
                  <div class="mb-4">
                    <label for="certification-issue-date" class="block text-sm font-medium text-gray-700">Issue
                      Date</label>
                    <Datepicker v-model="certification.graduate_certification_issue_date"
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                      placeholder="Select issue date" required />
                  </div>
                  <div class="mb-4">
                    <label for="certification-expiry-date" class="block text-sm font-medium text-gray-700">Expiry
                      Date</label>
                    <Datepicker v-model="certification.graduate_certification_expiry_date"
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                      placeholder="Select expiry date" :disabled="noExpiryDate" />
                    <div class="mt-2">
                      <input type="checkbox" id="no-expiry-date" v-model="noExpiryDate" class="mr-2" />
                      <label for="no-expiry-date" class="text-sm text-gray-700">No expiry date</label>
                    </div>
                  </div>
                  <div class="mb-4">
                    <label for="certification-credential-url" class="block text-sm font-medium text-gray-700">Credential
                      URL</label>
                    <input type="url" id="certification-credential-url"
                      v-model="certification.graduate_certification_credential_url"
                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                      :disabled="noCredentialUrl">
                    <div class="mt-2">
                      <input type="checkbox" id="no-credential-url" v-model="noCredentialUrl" class="mr-2" />
                      <label for="no-credential-url" class="text-sm text-gray-700">No Credential URL</label>
                    </div>
                  </div>
                  <div class="mb-4">
                    <label for="certification-file" class="block text-sm font-medium text-gray-700">Upload File</label>
                    <input type="file" id="certification-file" @input="form.avatar = $event.target.files[0]"
                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                  </div>
                  <div class="flex justify-end">
                    <button type="submit"
                      class="w-full bg-indigo-600 text-white py-2 rounded-md flex items-center justify-center">
                      <i class="fas fa-save mr-2"></i>
                      Update Certification
                    </button>
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
                <button class="bg-indigo-600 text-white px-4 py-2 rounded flex items-center"
                  @click="isAddAchievementModalOpen = true">
                  <i class="fas fa-plus mr-2"></i>
                  Add Achievement
                </button>
              </div>
              <p class="text-gray-600 mb-6">Showcase your awards and recognition</p>
              <div v-if="achievementEntries.length > 0" class="bg-white p-8 rounded-lg shadow mb-4 relative">
                <div v-for="(entry, index) in achievementEntries" :key="entry.id"
                  class="flex justify-between items-center">
                  <div>
                    <h2 class="text-xl font-bold">{{ entry.graduate_achievement_title }}</h2>
                    <p class="text-gray-600">{{ entry.graduate_achievement_issuer }}</p>
                    <div class="flex items-center text-gray-600 mt-2">
                      <i class="far fa-calendar-alt mr-2"></i>
                      <span>{{ entry.graduate_achievement_date }}</span>
                    </div>
                    <p class="mt-2">{{ entry.graduate_achievement_description }}</p>
                    <p class="mt-2">URL: {{ entry.graduate_achievement_url }}</p>
                    <p class="mt-2">Type: {{ entry.graduate_achievement_type }}</p>
                  </div>
                  <p class="mt-2" v-if="entry.file_path">
                    File: <a :href="`/storage/${entry.file_path}`" class="text-blue-600 hover:underline"
                      target="_blank">Download</a>
                  </p>
                  <div class="absolute top-2 right-2 flex space-x-2">
                    <button class="text-gray-600" @click="openUpdateAchievementModal(entry, index)">
                      <i class="fas fa-pen"></i>
                    </button>
                    <button class="text-red-600" @click="removeAchievement(entry.id)">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </div>
              </div>

              <!-- If no education entries exist -->
              <div v-else class="bg-white p-8 rounded-lg shadow mb-4">
                <p class="text-gray-600">No achievement entries added yet.</p>
              </div>
            </div>


            <!-- Add Achievement Modal -->
            <div v-if="isAddAchievementModalOpen"
              class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
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
                      <input type="text" v-model="achievement.graduate_achievement_title"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="e.g. Harvard University" required>
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Issuer</label>
                      <input type="text" v-model="achievement.graduate_achievement_issuer"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="e.g. Bachelor of Science" required>
                    </div>

                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Date</label>
                      <Datepicker v-model="achievement.graduate_achievement_date"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="Select start date" required />
                    </div>

                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Description</label>
                      <textarea v-model="achievement.graduate_achievement_description"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        rows="3" placeholder="Describe your experience..."></textarea>
                    </div>

                    <div class="mb-4">
                      <label for="achievement-url" class="block text-sm font-medium text-gray-700">
                        Credential URL
                      </label>
                      <input type="url" id="certification-credential-url" v-model="achievement.graduate_achievement_url"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div class="mb-4">
                      <label for="achievementType" class="block text-gray-700">AchievementType</label>
                      <select id="achievementType" v-model="achievementType"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                        <option value="" enabled>Select achievement type</option>
                        <option ption value="Award">Award</option>
                        <option value="Recognition">Recognition</option>
                        <option value="Publication">Publication</option>
                        <option value="Patent">Patent</option>
                        <option value="Other">Other</option>
                      </select>
                    </div>
                    <div class="mb-4">
                      <label for="achievement-file" class="block text-sm font-medium text-gray-700">Upload File</label>
                      <input type="file" id="achievement-file" @input="form.avatar = $event.target.files[0]"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="flex justify-end">
                      <button type="submit"
                        class="w-full bg-indigo-600 text-white py-2 rounded-md flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>
                        Add Achievement
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- Update Achievement Modal -->
            <div v-if="isUpdateAchievementModalOpen"
              class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
              <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                  <h2 class="text-xl font-semibold">Update Achievement</h2>
                  <button class="text-gray-500 hover:text-gray-700" @click="closeUpdateAchievementModal">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
                <p class="text-gray-600 mb-4">Update details about your achievement</p>
                <div class="max-h-96 overflow-y-auto">
                  <form @submit.prevent="updateAchievement">
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Title</label>
                      <input type="text" v-model="achievement.graduate_achievement_title"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="e.g. Best Employee Award" required />
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Issuer</label>
                      <input type="text" v-model="achievement.graduate_achievement_issuer"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="e.g. Tech Corp" required />
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Date</label>
                      <Datepicker v-model="achievement.graduate_achievement_date"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="Select date" required />
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Description</label>
                      <textarea v-model="achievement.graduate_achievement_description"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        rows="3" placeholder="Describe your achievement..."></textarea>
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">URL</label>
                      <input type="url" v-model="achievement.graduate_achievement_url"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        placeholder="e.g. https://example.com" />
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 font-medium mb-2">Type</label>
                      <select v-model="achievement.graduate_achievement_type"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600">
                        <option value="" disabled>Select type</option>
                        <option value="Award">Award</option>
                        <option value="Recognition">Recognition</option>
                        <option value="Publication">Publication</option>
                        <option value="Patent">Patent</option>
                        <option value="Other">Other</option>
                      </select>
                    </div>
                    <div class="mb-4">
                      <label for="achievement-file" class="block text-sm font-medium text-gray-700">Upload File</label>
                      <input type="file" id="achievement-file" @input="form.avatar = $event.target.files[0]"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="flex justify-end">
                      <button type="submit"
                        class="w-full bg-indigo-600 text-white py-2 rounded-md flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>
                        Update Achievement
                      </button>
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
                <button class="bg-indigo-600 text-white px-4 py-2 rounded flex items-center"
                  @click="isAddTestimonialsModalOpen = true">
                  <i class="fas fa-plus mr-2"></i>
                  Add Testimonials
                </button>
              </div>
              <p class="text-gray-600 mb-6">Recommendations from colleagues and clients</p>
              <div v-if="testimonialsEntries.length > 0" class="bg-white p-8 rounded-lg shadow mb-4 relative">
                <div v-for="(entry, index) in testimonialsEntries" :key="entry.id"
                  class="flex justify-between items-center">
                  <div>
                    <h2 class="text-xl font-bold">{{ entry.graduate_testimonials_name }}</h2>
                    <p class="text-gray-600">{{ entry.graduate_testimonials_role_title }}</p>
                    <p class="mt-2">{{ entry.graduate_testimonials_testimonial }}</p>
                  </div>
                  <div class="absolute top-2 right-2 flex space-x-2">
                    <button class="text-gray-600" @click="openUpdateTestimonialsModal(entry, index)">
                      <i class="fas fa-pen"></i>
                    </button>
                    <button class="text-red-600" @click="removeTestimonials(entry.id)">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </div>
              </div>

              <!-- If no testimonials entries exist -->
              <div v-else class="bg-white p-8 rounded-lg shadow mb-4">
                <p class="text-gray-600">No testimonial entries added yet.</p>
              </div>
            </div>

            <!-- Add Testimonials Modal -->
            <div v-if="isAddTestimonialsModalOpen"
              class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
              <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                  <h2 class="text-xl font-semibold">Add Testimonials</h2>
                  <button class="text-gray-500 hover:text-gray-700" @click="closeAddTestimonialsModal">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
                <p class="text-gray-600 mb-4">Add a recommendation from a colleague or client</p>
                <form @submit.prevent="addTestimonials">
                  <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Name</label>
                    <input type="text" v-model="testimonials.graduate_testimonials_name"
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                      placeholder="e.g. John Doe" required />
                  </div>
                  <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Role/Title</label>
                    <input type="text" v-model="testimonials.graduate_testimonials_role_title"
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                      placeholder="e.g. Manager" required />
                  </div>
                  <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Testimonial</label>
                    <textarea v-model="testimonials.graduate_testimonials_testimonial"
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                      rows="3" placeholder="Write the testimonial here..." required></textarea>
                  </div>
                  <div class="flex justify-end">
                    <button type="submit"
                      class="w-full bg-indigo-600 text-white py-2 rounded-md flex items-center justify-center">
                      <i class="fas fa-save mr-2"></i>
                      Add Testimonial
                    </button>
                  </div>
                </form>
              </div>
            </div>

            <!-- Update Testimonials Modal -->
            <div v-if="isUpdateTestimonialsModalOpen"
              class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
              <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                  <h2 class="text-xl font-semibold">Update Testimonials</h2>
                  <button class="text-gray-500 hover:text-gray-700" @click="closeUpdateTestimonialsModal">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
                <p class="text-gray-600 mb-4">Update a recommendation from a colleague or client</p>
                <form @submit.prevent="updateTestimonials">
                  <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Name</label>
                    <input type="text" v-model="testimonials.graduate_testimonials_name"
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                      placeholder="e.g. John Doe" required />
                  </div>
                  <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Role/Title</label>
                    <input type="text" v-model="testimonials.graduate_testimonials_role_title"
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                      placeholder="e.g. Manager" required />
                  </div>
                  <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Testimonial</label>
                    <textarea v-model="testimonials.graduate_testimonials_testimonial"
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                      rows="3" placeholder="Write the testimonial here..." required></textarea>
                  </div>
                  <div class="flex justify-end">
                    <button type="submit"
                      class="w-full bg-indigo-600 text-white py-2 rounded-md flex items-center justify-center">
                      <i class="fas fa-save mr-2"></i>
                      Update Testimonial
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- Employment Preferences Section -->
          <div v-if="activeSection === 'employment'" class="flex flex-col lg:flex-row">
            <div class="w-full lg:w-1/1 mb-6 lg:mb-0">
              <!-- Saved Preferences Container -->
              <div v-if="savedPreferences" class="mb-6 p-4 border border-gray-300 rounded-lg bg-gray-50">
                <h2 class="text-xl font-semibold mb-4">Saved Employment Preferences</h2>
                <p><strong>Job Types:</strong> {{ savedPreferences.jobTypes.join(', ') }}</p>
                <p><strong>Salary Expectations:</strong> {{ savedPreferences.salaryExpectations.currency }} {{
                  savedPreferences.salaryExpectations.range }} {{ savedPreferences.salaryExpectations.frequency }}</p>
                <p><strong>Preferred Locations:</strong> {{ savedPreferences.preferredLocations.join(', ') }}</p>
                <p><strong>Work Environment:</strong> {{ savedPreferences.workEnvironment.join(', ') }}</p>
                <p><strong>Availability:</strong> {{ savedPreferences.availability.join(', ') }}</p>
                <p><strong>Additional Notes:</strong> {{ savedPreferences.additionalNotes }}</p>
              </div>
              <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-semibold mb-4">Employment Preferences</h1>
              </div>
              <p class="text-gray-600 mb-6">Set your job preferences and requirements</p>
              <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Job Types</h2>
                <div class="flex flex-wrap gap-2">
                  <button v-for="type in ['Full-time', 'Part-time', 'Contract', 'Freelance', 'Internship']" :key="type"
                    :class="employmentPreferences.jobTypes.includes(type) ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700'"
                    class="px-4 py-2 rounded-full hover:bg-indigo-700" @click="toggleJobType(type)">
                    {{ type }}
                  </button>
                </div>
              </div>
              <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Salary Expectations</h2>
                <div class="flex items-center gap-2">
                  <select v-model="employmentPreferences.salaryExpectations.range"
                    class="border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-indigo-600">
                    <option value="" disabled>Select expected salary range</option>
                    <option value="below_20000">Below ₱20,000</option>
                    <option value="20000_30000">₱20,000 - ₱30,000</option>
                    <option value="30000_40000">₱30,000 - ₱40,000</option>
                    <option value="40000_50000">₱40,000 - ₱50,000</option>
                    <option value="50000_above">₱50,000 and above</option>
                  </select>
                  <select v-model="employmentPreferences.salaryExpectations.currency"
                    class="border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-indigo-600">
                    <option>PESO</option>
                    <option>USD</option>
                  </select>
                  <select v-model="employmentPreferences.salaryExpectations.frequency"
                    class="border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-indigo-600">
                    <option>per year</option>
                    <option>per month</option>
                    <option>per hour</option>
                  </select>
                </div>
              </div>
              <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Preferred Locations</h2>
                <div class="flex flex-wrap gap-2">
                  <span v-for="location in employmentPreferences.preferredLocations" :key="location"
                    class="bg-gray-200 text-gray-700 px-4 py-2 rounded-full">
                    {{ location }}
                  </span>
                  <button class="bg-indigo-600 text-white px-4 py-2 rounded-full hover:bg-indigo-700"
                    @click="isAddLocationModalOpen = true">
                    + Add Location
                  </button>
                </div>
              </div>
              <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Work Environment</h2>
                <div class="flex flex-wrap gap-8">
                  <label class="flex items-center">
                    <input type="checkbox" value="Remote" v-model="employmentPreferences.workEnvironment"
                      class="form-checkbox text-indigo-600 focus:ring-indigo-600" />
                    <span class="ml-2 text-gray-700">Remote</span>
                  </label>
                  <label class="flex items-center">
                    <input type="checkbox" value="Hybrid" v-model="employmentPreferences.workEnvironment"
                      class="form-checkbox text-indigo-600 focus:ring-indigo-600" />
                    <span class="ml-2 text-gray-700">Hybrid</span>
                  </label>
                  <label class="flex items-center">
                    <input type="checkbox" value="On-site" v-model="employmentPreferences.workEnvironment"
                      class="form-checkbox text-indigo-600 focus:ring-indigo-600" />
                    <span class="ml-2 text-gray-700">On-site</span>
                  </label>
                </div>
              </div>
              <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Availability</h2>
                <div class="flex flex-wrap gap-8">
                  <label class="flex items-center">
                    <input type="checkbox" value="Immediately" v-model="employmentPreferences.availability"
                      class="form-checkbox text-indigo-600 focus:ring-indigo-600" />
                    <span class="ml-2 text-gray-700">Immediately</span>
                  </label>
                  <label class="flex items-center">
                    <input type="checkbox" value="2 weeks notice" v-model="employmentPreferences.availability"
                      class="form-checkbox text-indigo-600 focus:ring-indigo-600" />
                    <span class="ml-2 text-gray-700">2 weeks notice</span>
                  </label>
                  <label class="flex items-center">
                    <input type="checkbox" value="1 month notice" v-model="employmentPreferences.availability"
                      class="form-checkbox text-indigo-600 focus:ring-indigo-600" />
                    <span class="ml-2 text-gray-700">1 month notice</span>
                  </label>
                </div>
              </div>
              <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Additional Notes</h2>
                <textarea v-model="employmentPreferences.additionalNotes"
                  class="border border-gray-300 rounded w-full px-4 py-2 focus:ring-2 focus:ring-indigo-600" rows="4"
                  placeholder="Any other preferences or requirements..."></textarea>
              </div>
              <div class="flex space-x-4">
                <button class="bg-indigo-600 text-white px-6 py-3 rounded-lg flex items-center"
                  @click="saveEmploymentPreferences">
                  <i class="fas fa-save mr-2"></i> Save Preferences
                </button>
                <button class="bg-gray-300 text-gray-700 px-6 py-3 rounded-lg flex items-center hover:bg-gray-400"
                  @click="resetEmploymentPreferences">
                  <i class="fas fa-undo mr-2"></i> Reset Preferences
                </button>
              </div>
            </div>

            <!-- Add Location Modal -->
            <div v-if="isAddLocationModalOpen"
              class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
              <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                  <h2 class="text-xl font-semibold">Add Location</h2>
                  <button class="text-gray-500 hover:text-gray-700" @click="closeAddLocationModal">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
                <p class="text-gray-600 mb-4">Add a preferred location for employment</p>
                <form @submit.prevent="addPreferredLocation">
                  <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Location</label>
                    <input type="text" v-model="newLocation"
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                      placeholder="e.g. New York, NY" required />
                  </div>
                  <div class="flex justify-end">
                    <button type="submit"
                      class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Add</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- Career Goals Section-->
          <div v-if="activeSection === 'career-goals'" class="flex flex-col lg:flex-row">
            <div class="w-full lg:w-1/1 mb-6 lg:mb-0">
              <!-- Saved Career Goals Container -->
              <div v-if="savedCareerGoals" class="mb-6 p-4 border border-gray-300 rounded-lg bg-gray-50">
                <h2 class="text-xl font-semibold mb-4">Saved Career Goals</h2>
                <p><strong>Short-term Goals:</strong> {{ savedCareerGoals.shortTermGoals }}</p>
                <p><strong>Long-term Goals:</strong> {{ savedCareerGoals.longTermGoals }}</p>
                <p><strong>Industries of Interest:</strong> {{ savedCareerGoals.industriesOfInterest.join(', ') }}</p>
                <p><strong>Career Path:</strong> {{ savedCareerGoals.careerPath }}</p>
              </div>

              <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-semibold mb-4">Career Goals</h1>
              </div>
              <p class="text-gray-600 mb-6">Define your short and long-term career aspirations</p>
              <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Short-term Goals (1-2 years)</h2>
                <textarea class="w-full p-8 border border-gray-300 rounded-lg" rows="3"
                  v-model="careerGoals.shortTermGoals">
                </textarea>
              </div>
              <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Long-term Goals (3-5 years)</h2>
                <textarea class="w-full p-8 border border-gray-300 rounded-lg" rows="3"
                  v-model="careerGoals.longTermGoals">
                </textarea>
              </div>
              <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Industries of Interest</h2>
                <div class="flex flex-wrap gap-2">
                  <span v-for="industry in careerGoals.industriesOfInterest" :key="industry"
                    class="bg-gray-200 text-gray-700 px-4 py-2 rounded-full">
                    {{ industry }}
                  </span>
                  <button class="bg-indigo-600 text-white px-4 py-2 rounded-full hover:bg-indigo-700"
                    @click="openAddIndustryModal">
                    + Add Industry
                  </button>
                </div>
              </div>
              <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Career Path</h2>
                <input type="text"
                  class="w-full border border-gray-300 rounded-md p-2 outline-none focus:ring-2 focus:ring-indigo-600"
                  v-model="careerGoals.careerPath" placeholder="Enter your career path" />
              </div>
              <div class="flex space-x-4">
                <button class="bg-indigo-600 text-white px-6 py-3 rounded-lg flex items-center"
                  @click="saveCareerGoals">
                  <i class="fas fa-save mr-2"></i> Save Goals
                </button>
                <button class="bg-gray-300 text-gray-700 px-6 py-3 rounded-lg flex items-center hover:bg-gray-400"
                  @click="resetCareerGoals">
                  <i class="fas fa-undo mr-2"></i> Reset Goals
                </button>
              </div>
            </div>

            <!-- Add Industry Modal -->
            <div v-if="isAddIndustryModalOpen"
              class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
              <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                  <h2 class="text-xl font-semibold">Add Industry</h2>
                  <button class="text-gray-500 hover:text-gray-700" @click="closeAddIndustryModal">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
                <p class="text-gray-600 mb-4">Add a preferred industry of interest to your profile.</p>
                <form @submit.prevent="addPreferredIndustry">
                  <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Industry</label>
                    <input type="text" v-model="newIndustry"
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"
                      placeholder="e.g. Technology, Healthcare" required />
                  </div>
                  <div class="flex justify-end">
                    <button type="submit"
                      class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Add</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- Resume Section -->
          <div v-if="activeSection === 'resume'" class="flex flex-col lg:flex-row">
            <div class="w-full lg:w-1/1 mb-6 lg:mb-0">
              <h2 class="text-xl font-semibold mb-4">Resume</h2>
              <p class="text-gray-600 mb-4">Upload and manage your resume</p>
              <div class="bg-white p-6 rounded-lg shadow-md border border-gray-300">
                <!-- Display Uploaded Resume -->
                <div v-if="resume.file"
                  class="flex items-center justify-between border border-gray-300 rounded-lg p-8 mb-4">
                  <div class="flex items-center">
                    <i class="fas fa-file-alt text-gray-500 text-2xl mr-4"></i>
                    <span class="text-gray-700 font-medium">{{ resume.fileName }}</span>
                  </div>
                  <button class="text-red-600 hover:text-red-800" @click="removeResume">
                    <i class="fas fa-trash"></i>
                  </button>
                </div>

                <!-- If No Resume Uploaded -->
                <div v-else class="flex items-center justify-between border border-gray-300 rounded-lg p-8 mb-4">
                  <div class="flex items-center">
                    <i class="fas fa-file-alt text-gray-500 text-2xl mr-4"></i>
                    <span class="text-gray-500">No resume uploaded yet</span>
                  </div>
                </div>

                <!-- Upload Resume Button -->
                <label for="resume-upload"
                  class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-600 text-center cursor-pointer block">
                  <i class="fas fa-upload mr-2"></i> Upload New Resume
                </label>
                <input type="file" id="resume-upload" class="hidden" accept=".pdf,.doc,.docx" @change="uploadResume" />
              </div>
            </div>
          </div>
        </div>

        <teleport to="body">
          <div v-if="isDeleteConfirmationModalOpen"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded shadow-lg">
              <h2 class="text-lg font-bold">Confirm Deletion</h2>
              <p>Are you sure you want to delete this item?</p>
              <div class="flex justify-end mt-4 space-x-2">
                <button @click="confirmDeletion"
                  class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete</button>
                <button @click="closeDeleteConfirmationModal"
                  class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
              </div>
            </div>
          </div>
          <div v-if="isProfileModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded shadow-lg">
              <h2 class="text-lg font-bold">Profile Information Updated</h2>
              <p>Your profile information has been successfully updated.</p>
              <button @click="closeProfileModal" class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded">Close</button>
            </div>
          </div>
          <div v-if="isNoChangesModalOpen"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded shadow-lg">
              <h2 class="text-lg font-bold">No Changes Made</h2>
              <p>Your profile information has not been changed.</p>
              <button @click="closeNoChangesModal"
                class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded">Close</button>
            </div>
          </div>
          <div v-if="isPasswordModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded shadow-lg">
              <h2 class="text-lg font-bold">Password Updated</h2>
              <p>Your password has been successfully updated.</p>
              <button @click="closePasswordModal" class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded">Close</button>
            </div>
          </div>
        </teleport>
      </div>
    </div>
  </Graduate>
</template>