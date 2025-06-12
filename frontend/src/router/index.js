import { createRouter, createWebHistory } from 'vue-router';
import Login from '@/components/Login.vue';
import Register from '@/components/Register.vue';
import Tasks from '@/components/Tasks.vue';

const routes = [
  { path: '/', component: Login },
  { path: '/register', component: Register },
  { path: '/todo', component: Tasks }
];

export default createRouter({
  history: createWebHistory(),
  routes
});
