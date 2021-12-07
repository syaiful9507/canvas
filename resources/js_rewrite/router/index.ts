import {createRouter, createWebHistory, RouteRecordRaw} from 'vue-router'
import Dashboard from "@/views/Dashboard.vue";

const routes: Array<RouteRecordRaw> = [
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard,
    },
    {
        path: '/:catchAll(.*)',
        redirect: '/',
    },
]

const router = createRouter({
    history: createWebHistory('canvas'),
    routes: routes
})

export default router;
