<script setup>
import { defineEmits, onMounted, ref } from 'vue';
import axios from 'axios';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/solid';
import ContactDetails from './ContactDetails.vue'; // Import the ContactDetails component

const props = defineProps({
  categories: Array,
  customer: Object
});

const emit = defineEmits(['update:customer', 'update:categories', 'closeModal']);

const contacts = ref([]);

const showContactModal = ref(false);
const currentContact = ref(null);

function updateCustomerField(field, value) {
  emit('update:customer', { ...props.customer, [field]: value });
}

function updateCategories(value) {
  emit('update:categories', value);
}

// Fetch contacts when the component is mounted
onMounted(async () => {
  try {
    const response = await axios.get(`/customer/${props.customer.id}/contacts`);
    contacts.value = response.data;
  } catch (error) {
    console.error('Error fetching contacts:', error);
  }
});

function closeModal() {
  emit('closeModal');
}

async function saveCustomer() {
  try {
    let response;
    if (props.customer.id) {
      // Update existing customer
      response = await axios.post(`/customer/${props.customer.id}/update`, props.customer);
      console.log('Customer updated successfully:', response.data);
    } else {
      // Create new customer
      response = await axios.post('/customer/store', props.customer);
      console.log('New customer created successfully:', response.data);
      // Update the customer prop with the new customer details
      emit('update:customer', response.data);
    }
  } catch (error) {
    console.error('Error saving customer:', error);
  }
}

async function updateCustomer() {
  try {
    const response = await axios.post(`/customer/${props.customer.id}/update`, props.customer);
    console.log('Customer updated successfully:', response.data);
  } catch (error) {
    console.error('Error updating customer:', error);
  }
}

function openContactModal(contact = null) {
  currentContact.value = contact;
  showContactModal.value = true;
}

function closeContactModal() {
  showContactModal.value = false;
}

// Listen for the close event from ContactDetails and close the modal
function handleContactDetailsClose() {
  closeContactModal();
}

async function deleteContact(contactId) {
  const confirmed = confirm('Are you sure you want to delete this contact?');
  if (!confirmed) return;

  try {
    await axios.post('/contact/delete', { id: contactId });
    console.log('Contact deleted successfully');
    // Remove the contact from the contacts list
    contacts.value = contacts.value.filter(contact => contact.id !== contactId);
  } catch (error) {
    console.error('Error deleting contact:', error);
  }
};
</script>

<template>
    <div class="bg-gray-200 p-4">
        <div class="flex flex-row mb-2 justify-between">
            <div className="text-4xl text-[#1D78E6]">
                Customers - Detail
            </div>
            <div>
                <button @click="closeModal" class="mt-4 bg-gray-300 hover:bg-gray-400 text-white px-2 py-1 rounded-md mr-4">Back</button>
                <button @click="save" class="bg-[#1D78E6] hover:bg-blue-600 px-2 py-1 rounded-md text-white">Save</button>
            </div>
        </div>
      <div class="flex flex-row justify-between">
        
        <div class="w-1/2 flex flex-col p-2 border-2 bg-gray-100 border-gray-300 m-1 rounded-md">
            <div class="text-[#1D78E6] text-2xl mb-1">General</div>
            <div class="w-full flex flex-col">
                <div class="text-lg">Name:</div>
                <input id="customer-name" class="w-full border border-gray-200 rounded-md" :value="customer.name" @input="updateCustomerField('name', $event.target.value)" type="text">
            </div>
            <div class="w-full flex flex-col mt-4">
                <div for="customer-reference" class="text-lg">Reference:</div>
                <input id="reference"  class="w-full border border-gray-200 rounded-md" :value="customer.reference" @input="updateCustomerField('reference', $event.target.value)" type="text">
            </div>
            <div class="w-full flex flex-col mt-4">
                <div for="catagory_id" class="text-lg">Category:</div>
                <select id="catagory_id"  class="w-full border border-gray-300 rounded-md" value="customer.category_id" @change="updateCustomerField('category_id', $event.target.value)">
                <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.category }}</option>
                </select>
            </div>

        </div>
        <div class="w-1/2 flex flex-col  bg-gray-100 border-2 border-gray-300 m-1 p-2 rounded-md">
            <div class="text-[#1D78E6] text-2xl mb-1">Details</div>
            <div class="w-full flex flex-col">
                <div class="text-lg">Start Date</div>
                <input class="w-full border border-gray-200 rounded-md" value="customer.startDate" type="date" @change="updateCustomerField('startDate', $event.target.value)" />
            </div>
            <div class="w-full flex flex-col mt-4">
                <div class="text-lg">Description</div>
                <textarea class="w-full border border-gray-200 rounded-md bg-white" @input="updateCustomerField('description', $event.target.value)">{{ customer.description }}</textarea>
            </div>

        </div>
      </div>
      
      
      
      
      <div class="contacts border-2 border-gray-200 mt-4 w-full">
        <div class="flex flex-row justify-between"><div class="text-[#1D78E6] text-2xl mb-1">Contacts</div>
    <div><button @click="openContactModal" class="bg-[#1D78E6] hover:bg-blue-600 px-2 py-1 rounded-md text-white">Add Contact</button></div>
    </div>
        <table class="w-full rounded-md border border-gray-300 p-2">
          <thead>
            <tr class="bg-gray-300">
              <th class="text-left pl-1">First Name</th>
              <th class="text-left pl-1">Last name</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="contact in contacts" :key="contact.id" class="p-2 odd:bg-gray-200 even:bg-gray-100">
              <td class="w-2/5 p-2" >{{ contact.firstName }}</td>
              <td class="w-2/5 p-2">{{ contact.lastName }}</td>
              <td class="w-1/5 p-2"> <div class="flex flex-row justify-between">
                <button @click="openContactModal(contact)" class="edit-button">
                  <PencilSquareIcon class="h-5 w-5 mr-2 text-blue-500" />
                </button>
                <button @click="deleteContact(contact.id)" class="edit-button">
                  <TrashIcon class="h-5 w-5 mr-2 text-red-500" />
                </button>
              </div></td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Modal for Contact Details -->
      <div v-if="showContactModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
        <div class="bg-white p-4 rounded-md shadow-lg w-1/2">
          <ContactDetails :contact="currentContact" :customerId="customer.id" @close="handleContactDetailsClose" />
        </div>
      </div>
      
    </div>
  </template>
  
  <script>
  export default {
    methods: {
      save() {
        // Call the saveCustomer function when saving
        saveCustomer();
        console.log('Saving customer:', this.customer);
      }
    }
  }
  </script>