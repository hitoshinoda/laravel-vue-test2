import { createRouter, createWebHistory } from 'vue-router';
import HomePage from '../components/HomePage.vue';
import ProfilePage from '../components/ProfilePage.vue';

const routes = [
    { path: '/', name: 'home', component: HomePage },
    { path: '/profile', name: 'profile', component: ProfilePage },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
