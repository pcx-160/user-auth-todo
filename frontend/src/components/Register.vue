<template>
  <div class="min-h-screen bg-gray-100 flex items-center justify-center">
    <!-- Container for split screen -->
    <div class="w-full max-w-4xl flex border border-gray-300 rounded-lg overflow-hidden">
      
      <!-- Left side: Register Form -->
      <div class="w-1/2 bg-white p-16 flex flex-col justify-center items-center">
        <h2 class="text-2xl font-semibold text-center mb-6">Register</h2>

        <!-- Username input -->
        <input 
          v-model="username" 
          type="text" 
          placeholder="Username" 
          class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        />

        <!-- Password input -->
        <input 
          v-model="password" 
          type="password" 
          placeholder="Password" 
          class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        />

        <!-- Register Button -->
        <button 
          @click="register"
          class="w-full py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
        >
          Register
        </button>

        <!-- Link to Login page -->
        <div class="mt-6 text-center">
          <router-link 
            to="/" 
            class="text-blue-500 hover:text-blue-700 font-semibold"
          >
            Already have an account?
          </router-link>
        </div>
        <!-- Message -->
        <p class="mt-4 text-center text-gray-600">{{ message }}</p>
      </div>

      <!-- Right side: Optional content (image or background) -->
      <div class="w-1/2 bg-blue-500 flex items-center justify-center text-white">
        <div class="text-center p-12">
          <h2 class="text-3xl font-semibold">Welcome to Our Platform!</h2>
          <p class="mt-4">Join us and experience something amazing</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const username = ref('');
const password = ref('');
const message = ref('');

const register = async () => {
  try {
    const res = await axios.post('http://localhost/apptask/backend/register.php', {
      username: username.value,
      password: password.value
    });
    username.value = "";
    password.value = "";
    message.value = res.data.message;
  } catch (err) {
    message.value = err.response?.data?.message || 'Registration failed';
    console.log(err);
  }
};
</script>
