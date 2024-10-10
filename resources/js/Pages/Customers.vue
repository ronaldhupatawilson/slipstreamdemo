<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/solid';
import { defineProps, ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import CustomerDetails from '@/Components/CustomerDetails.vue';
const props = defineProps({
  customers: Array
});

const searchText = ref('');
const selectedCategory = ref('');
const categories = ref(props.categories);
const customerList = ref(props.customers);
const currentCustomer = ref(null);

const filteredCustomers = computed(() => {
  return customerList.value.filter(customer => {
    const matchesSearch = customer.name.toLowerCase().includes(searchText.value.toLowerCase());
    const matchesCategory = selectedCategory.value ? customer.category === selectedCategory.value : true;
    return matchesSearch && matchesCategory;
  });
});

function debounce(func, wait) {
  let timeout;
  return function(...args) {
    const context = this;
    clearTimeout(timeout);
    timeout = setTimeout(() => func.apply(context, args), wait);
  };
}

const fetchCustomers = async () => {
  try {
    const response = await axios.get(`/customers/${searchText.value}`);
    customerList.value = response.data;
  } catch (error) {
    console.error('Error fetching customers:', error);
  }
};

const debouncedFetchCustomers = debounce(fetchCustomers, 700);

const handleEdit = (customer) => {
  currentCustomer.value = customer;
  toggleModal();
};

const handleDelete = async (customer) => {
  const confirmed = confirm(`Delete customer ${customer.name}?`);
  if (confirmed) {
    try {
        await axios.post('/customer/delete/', { id: customer.id });
      customerList.value = customerList.value.filter(c => c.id !== customer.id);
    } catch (error) {
      console.error('Error deleting customer:', error);
    }
  }
};

const isModalVisible = ref(false);

const toggleModal = () => {
    if(isModalVisible.value){
        currentCustomer.value = null; // Reset currentCustomer to null
    }
  isModalVisible.value = !isModalVisible.value;
};

onMounted(() => {
  categories.value = [...new Set(customerList.value.map(customer => customer.category))].sort();
});

watch(searchText, () => {
  debouncedFetchCustomers();
});
</script>

<template>
  <Head title="Customers" />
  <AuthenticatedLayout>
    <div class="m-16">
      <div class="flex justify-between mb-4">
        <div class="text-4xl font-bold text-[#0091E3]">Customers</div> 
        <button class="rounded-md bg-[#0091E3] text-white px-2" @click="toggleModal">
          <v-icon>mdi-plus</v-icon>Create
        </button>
      </div>
      <div class="flex mb-4 bg-gray-300 p-4 rounded-md">
        <input
          type="text"
          v-model="searchText"
          placeholder="Search by name"
          class="border p-2 mr-4 rounded-md"
        />
        <select v-model="selectedCategory" class="border p-2 min-w-48 bg-white rounded-md">
          <option value="">All Categories</option>
          <option v-for="category in categories" :key="category" :value="category">
            {{ category }}
          </option>
        </select>
      </div>
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Reference</th>
            <th>Category</th>
            <th>No. of Contacts</th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="customer in filteredCustomers" :key="customer.id">
            <td>{{ customer.name }}</td>
            <td>{{ customer.reference }}</td>
            <td>{{ customer.category }}</td>
            <td>{{ customer.contactscount }}</td>
            <td>
              <div class="flex flex-row justify-between">
                <button @click="handleEdit(customer)" class="edit-button">
                  <PencilSquareIcon class="h-5 w-5 mr-2 text-blue-500" />
                </button>
                <button @click="handleDelete(customer)" class="edit-button">
                  <TrashIcon class="h-5 w-5 mr-2 text-red-500" />
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AuthenticatedLayout> 
  <!-- Modal -->
  <div v-if="isModalVisible" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
    <div class="bg-white p-6 rounded-md shadow-md w-3/4">
      <CustomerDetails 
        :categories="categories" 
        :customer="currentCustomer" 
        @closeModal="toggleModal" 
      />
    </div>
  </div>
</template>

<style scoped>
table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  border: 1px solid #ddd;
  padding: 8px;
}

th {
  background-color: #f2f2f2;
  text-align: left;
}
</style>