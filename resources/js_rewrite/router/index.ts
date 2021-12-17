import {createRouter, createWebHistory, RouteRecordRaw} from 'vue-router'
import Dashboard from "@/views/Dashboard.vue";
import PostList from "@/views/PostList.vue";
import EditPost from "@/views/EditPost.vue";
import TagList from "@/views/TagList.vue";
import EditTag from "@/views/EditTag.vue";
import TopicList from "@/views/TopicList.vue";
import EditTopic from "@/views/EditTopic.vue";
import UserList from "@/views/UserList.vue";
import EditUser from "@/views/EditUser.vue";
import EditSettings from "@/views/EditSettings.vue";

const routes: Array<RouteRecordRaw> = [
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard,
    },
    {
        path: '/posts',
        name: 'posts',
        component: PostList,
    },
    {
        path: '/posts/create',
        name: 'create-post',
        component: EditPost,
    },
    {
        path: '/posts/:id/edit',
        name: 'edit-post',
        component: EditPost,
    },
    {
        path: '/tags',
        name: 'tags',
        component: TagList,
    },
    {
        path: '/tags/create',
        name: 'create-tag',
        component: EditTag,
    },
    {
        path: '/tags/:id/edit',
        name: 'edit-tag',
        component: EditTag,
    },
    {
        path: '/topics',
        name: 'topics',
        component: TopicList,
    },
    {
        path: '/topics/create',
        name: 'create-topic',
        component: EditTopic,
    },
    {
        path: '/topics/:id/edit',
        name: 'edit-topic',
        component: EditTopic,
    },
    {
        path: '/users',
        name: 'users',
        component: UserList,
    },
    {
        path: '/users/create',
        name: 'create-user',
        component: EditUser,
    },
    {
        path: '/users/:id/edit',
        name: 'edit-user',
        component: EditUser,
    },
    {
        path: '/settings',
        name: 'settings',
        component: EditSettings,
    },
    {
        path: '/:catchAll(.*)',
        redirect: '/',
    },
]

const router = createRouter({
    history: createWebHistory('canvas'), // TODO: Use the env() path instead of hard coding this
    routes: routes
})

export default router;
