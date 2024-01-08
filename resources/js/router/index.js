import AccountDashboard from '@/pages/AccountDashboard'
import AccountSettings from '@/pages/AccountSettings'
import PostList from '@/pages/PostList'
import ShowPost from '@/pages/ShowPost'
import ShowTag from '@/pages/ShowTag'
import ShowTopic from '@/pages/ShowTopic'
import ShowUser from '@/pages/ShowUser'
import TagList from '@/pages/TagList'
import TopicList from '@/pages/TopicList'
import UserList from '@/pages/UserList'
import { store } from '@/store'
import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/dashboard',
    name: 'dashboard',
    component: AccountDashboard
  },
  {
    path: '/posts',
    name: 'posts',
    component: PostList,
    props: route => ({
      author: route.query?.author,
      page: route.query?.page,
      sort: route.query?.sort,
      type: route.query?.type
    })
  },
  {
    path: '/posts/create',
    name: 'create-post',
    component: ShowPost
  },
  {
    path: '/posts/:id',
    name: 'show-post',
    component: ShowPost
  },
  {
    path: '/tags',
    name: 'tags',
    component: TagList,
    props: route => ({
      page: route.query?.page,
      sort: route.query?.sort,
      usage: route.query?.usage
    })
  },
  {
    path: '/tags/create',
    name: 'create-tag',
    component: ShowTag
  },
  {
    path: '/tags/:id',
    name: 'show-tag',
    component: ShowTag
  },
  {
    path: '/topics',
    name: 'topics',
    component: TopicList,
    props: route => ({
      page: route.query?.page,
      sort: route.query?.sort,
      usage: route.query?.usage
    })
  },
  {
    path: '/topics/create',
    name: 'create-topic',
    component: ShowTopic
  },
  {
    path: '/topics/:id',
    name: 'show-topic',
    component: ShowTopic
  },
  {
    path: '/users',
    name: 'users',
    component: UserList,
    props: route => ({
      role: route.query?.role,
      page: route.query?.page,
      sort: route.query?.sort
    })
  },
  {
    path: '/users/create',
    name: 'create-user',
    component: ShowUser
  },
  {
    path: '/users/:id',
    name: 'show-user',
    component: ShowUser
  },
  {
    path: '/settings',
    name: 'settings',
    component: AccountSettings
  },
  {
    path: '/:catchAll(.*)',
    redirect: 'dashboard'
  }
]

const router = createRouter({
  history: createWebHistory(store.state.config.path),
  routes
})

export default router
