import {createRouter, createWebHistory, RouteRecordRaw} from 'vue-router'

const routes: Array<RouteRecordRaw> = [
    {
        path: '/',
        name: 'dashboard',
        component: () => import(/* webpackChunkName: "dashboard" */ '../views/Dashboard.vue'),
    },
    {
        path: '/:catchAll(.*)',
        redirect: '/',
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes: routes
})

export default router;
