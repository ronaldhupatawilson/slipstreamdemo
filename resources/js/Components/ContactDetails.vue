<script setup>
import { defineProps } from 'vue';
import axios from 'axios';

const props = defineProps({
  contact: {
    type: Object,
    default: () => ({
      id: null,
      firstName: '',
      lastName: ''
    })
  }
});

const save = async () => {
  try {
    if (props.contact.id) {
      // Update existing contact
      await axios.post(`/contact/${props.contact.id}/update`, props.contact);
      console.log('Contact updated successfully');
    } else {
      // Store new contact
      await axios.post('/contact/store', props.contact);
      console.log('Contact stored successfully');
    }
  } catch (error) {
    console.error('Error saving contact:', error);
  }
};
</script>

<template>
    <div class="bg-gray-200 p-4">
        <div class="flex flex-row mb-2 justify-between">
            <div className="text-4xl text-[#1D78E6]">
                Contact - Detail
            </div>
            <div>
                <button @click="closeModal" class="mt-4 bg-gray-300 hover:bg-gray-400 text-white px-2 py-1 rounded-md mr-4">Back</button>
                <button @click="save" class="bg-[#1D78E6] hover:bg-blue-600 px-2 py-1 rounded-md text-white">Save</button>
            </div>
        </div>
        <div class="flex flex-col">
          <div class="w-full flex flex-col p-2 border-2 bg-gray-100 border-gray-300 m-1 rounded-md">
            <div class="text-[#1D78E6] text-2xl mb-1">General</div>
            <div class="w-full flex flex-col">
                <div class="text-lg">First Name:</div>
                <input id="customer-name" class="w-full border border-gray-200 rounded-md" v-model="props.contact.firstName" type="text">
            </div>
            <div class="w-full flex flex-col mt-4">
                <div for="contact-reference" class="text-lg">Last Name:</div>
                <input id="reference" class="w-full border border-gray-200 rounded-md" v-model="props.contact.lastName" type="text">
            </div>
        </div>
        </div>
    </div>
</template>

<script>
export default {
    methods: {
        closeModal() {
            // Emit close event to parent
            this.$emit('close');
        }
    }
}
</script>