<script setup>
import { ref } from 'vue'; // Ensure ref is imported
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';

// Initialize as a ref
const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="h-screen flex">
        <!-- Sidebar -->
        <div class="min-w-[300px] bg-[#1E40AF] flex flex-col">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-white">
                    <img src="/images/slipstreamLogo.webp" alt="Logo" class="w-full h-full">
                </h2>
            </div>
            <div class="flex flex-col text-white justify-center items-center text-center my-auto">
                <div class="mb-4">
                    <NavLink :href="route('dashboard')" :active="route().current('dashboard')" class="text-white text-4xl">Dashboard</NavLink>
                </div>
                <div>
                    <NavLink :href="route('customers')" :active="route().current('customers')" class="text-white text-4xl">Customers</NavLink>
                </div>
            </div>
            <!-- <div class="flex flex-row text-white justify-between items-center text-center m-4">
                <NavLink :href="route('profile.edit')" :active="route().current('profile.edit')" class="text-white text-3xl">Profile</NavLink>
                <NavLink :href="route('logout')" method="post" as="button" class="text-white text-3xl">Log Out</NavLink>
            </div> -->
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1">
                <slot />
            </main>
        </div>
    </div>
</template>
