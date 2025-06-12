<template>
  <div class="min-h-screen bg-gray-100 flex flex-col items-center py-12">
    <!-- Container for the to-do list -->
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-lg">
      <!-- Header with logged-in user information -->
      <div class="mb-6 text-center">
        <h2 class="text-2xl font-semibold mb-2">Your ToDo List</h2>
        <label class="block text-gray-600 mb-4">Logged in as: <span class="font-semibold text-blue-500">{{ login_as }}</span></label>
        <button 
          @click="logOut" 
          class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400"
        >
          Log Out
        </button>
      </div>

      <!-- Add Task Input -->
      <div class="mb-6">
        <input 
          v-model="newTask" 
          @keyup.enter="addTask" 
          placeholder="Add new task" 
          class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>

      <!-- To-Do List -->
      <ul class="space-y-4">
        <li 
      v-for="todo in todos" 
      :key="todo.id" 
      class="flex justify-between items-center p-3 bg-gray-50 border border-gray-200 rounded-lg"
    >
      <div class="flex-1">
        <template v-if="editingId === todo.id">
          <input 
            v-model="editingText" 
            class="w-full p-2 border border-blue-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </template>
        <template v-else>
          {{ todo.task }}
        </template>
      </div>
      <div class="ml-4 flex-shrink-0 space-x-2">
        <template v-if="editingId === todo.id">
          <button 
            @click="saveTask(todo.id)" 
            class="text-green-600 hover:text-green-800"
          >
            Save
          </button>
          <button 
            @click="cancelEdit" 
            class="text-gray-500 hover:text-gray-700"
          >
            Cancel
          </button>
        </template>
        <template v-else>
          <button 
            @click="editTask(todo.id)" 
            class="text-blue-500 hover:text-blue-700"
          >
            Edit
          </button>
          <button 
            @click="deleteTask(todo.id)" 
            class="text-red-500 hover:text-red-700"
          >
            Delete
          </button>
        </template>
      </div>
  </li>

      </ul>
    </div>
  </div>
</template>


<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();
const todos = ref([]);
const newTask = ref('');
const user_id = localStorage.getItem('user_id');
const login_as = localStorage.getItem('login_as');

const editingId = ref(null);
const editingText = ref('');


if (!user_id) router.push('/');

function logOut() {
  router.push('/');
}

const fetchTodos = async () => {
  const res = await axios.get(`http://localhost/apptask/backend/todos.php?user_id=${user_id}`);
  todos.value = res.data;
};

const addTask = async () => {
  if (!newTask.value) return;
  await axios.post('http://localhost/apptask/backend/todos.php', {
    user_id,
    task: newTask.value
  });
  newTask.value = '';
  fetchTodos();
};

const editTask = (id) => {
  const task = todos.value.find(todo => todo.id === id);
  editingId.value = id;
  editingText.value = task.task;
};

const saveTask = async (id) => {
  await axios.put('http://localhost/apptask/backend/todos.php', {
    id,
    task: editingText.value,
    user_id
  });
  editingId.value = null;
  editingText.value = '';
  fetchTodos();
};

const cancelEdit = () => {
  editingId.value = null;
  editingText.value = '';
};



const deleteTask = async (id) => {
  await axios.delete(`http://localhost/apptask/backend/todos.php?user_id=${user_id}&id=${id}`);
  fetchTodos();
};

onMounted(fetchTodos);
</script>
