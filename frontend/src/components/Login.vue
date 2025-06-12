<template>
  <div class="min-h-screen bg-gray-100 flex items-center justify-center">
    <!-- Container for split screen -->
    <div
      class="w-full max-w-4xl flex border border-gray-300 rounded-lg overflow-hidden"
    >
      <!-- Left side: Login Form -->
      <div
        class="w-1/2 bg-white p-16 flex flex-col justify-center items-center"
      >
        <h2 class="text-2xl font-semibold text-center mb-6">Login</h2>

        <input
          v-model="username"
          type="text"
          placeholder="Username"
          class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        />

        <input
          v-model="password"
          type="password"
          placeholder="Password"
          class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          @keyup.enter="login"
        />

        <button
          @click="login"
          class="w-full py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
        >
          Login
        </button>

        <div class="mt-6 text-center">
          <router-link
            to="/register"
            class="text-blue-500 hover:text-blue-700 font-semibold"
          >
            Don't have an account?
          </router-link>
        </div>
        <p class="mt-4 text-center text-gray-600">{{ message }}</p>
      </div>

      <!-- Right side: Optional content (image or background) -->
      <div
        class="w-1/2 bg-blue-500 hidden md:flex items-center justify-center text-white"
      >
        <div class="text-center p-12">
          <h2 class="text-3xl font-semibold">TODO APP!</h2>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const username = ref("");
const password = ref("");
const message = ref("");
const router = useRouter();

const login = async () => {
  try {
    const res = await axios.post("http://localhost/apptask/backend/login.php", {
      username: username.value,
      password: password.value,
    });
    localStorage.setItem("user_id", res.data.user_id);
    localStorage.setItem("login_as", username.value);
    router.push("/todo");
  } catch (err) {
    message.value = err.response?.data?.message || "Login failed";
  }
};
</script>
