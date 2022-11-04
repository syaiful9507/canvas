<template>
  <div class="hidden md:fixed md:inset-y-0 md:flex md:w-64 md:flex-col">
    <div
      class="flex min-h-0 flex-1 flex-col border-r border-gray-200 bg-gray-100"
    >
      <div class="flex flex-1 flex-col overflow-y-auto pt-5 pb-4">
        <div class="flex flex-shrink-0 items-center px-4">
          <AppLink :to="{ name: 'dashboard' }">
            <img
              class="h-8 w-auto"
              src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
              alt="Your Company"
            />
          </AppLink>
        </div>
        <nav class="mt-5 flex-1" aria-label="Sidebar">
          <div class="space-y-1 px-2">
            <AppLink
              :to="{ name: 'dashboard' }"
              class="group flex items-center px-2 py-2 text-sm font-medium rounded-md"
              active-class="bg-gray-200 text-gray-900"
              inactive-class="text-gray-600 hover:bg-gray-50 hover:text-gray-900"
            >
              <component
                :is="HomeIcon"
                :class="[
                  ['dashboard'].includes(route.name)
                    ? 'text-gray-500'
                    : 'text-gray-400 group-hover:text-gray-500',
                  'mr-3 flex-shrink-0 h-6 w-6',
                ]"
                aria-hidden="true"
              />
              {{ trans.dashboard }}
            </AppLink>
            <AppLink
              :to="{ name: 'users' }"
              class="group flex items-center px-2 py-2 text-sm font-medium rounded-md"
              active-class="bg-gray-200 text-gray-900"
              inactive-class="text-gray-600 hover:bg-gray-50 hover:text-gray-900"
            >
              <component
                :is="UserGroupIcon"
                :class="[
                  ['users', 'create-user', 'show-user'].includes(route.name)
                    ? 'text-gray-500'
                    : 'text-gray-400 group-hover:text-gray-500',
                  'mr-3 flex-shrink-0 h-6 w-6',
                ]"
                aria-hidden="true"
              />
              {{ trans.users }}
            </AppLink>
            <AppLink
              :to="{ name: 'posts' }"
              class="group flex items-center px-2 py-2 text-sm font-medium rounded-md"
              active-class="bg-gray-200 text-gray-900"
              inactive-class="text-gray-600 hover:bg-gray-50 hover:text-gray-900"
            >
              <component
                :is="BookmarkSquareIcon"
                :class="[
                  ['posts', 'create-post', 'show-post'].includes(route.name)
                    ? 'text-gray-500'
                    : 'text-gray-400 group-hover:text-gray-500',
                  'mr-3 flex-shrink-0 h-6 w-6',
                ]"
                aria-hidden="true"
              />
              {{ trans.posts }}
            </AppLink>
            <AppLink
              :to="{ name: 'tags' }"
              class="group flex items-center px-2 py-2 text-sm font-medium rounded-md"
              active-class="bg-gray-200 text-gray-900"
              inactive-class="text-gray-600 hover:bg-gray-50 hover:text-gray-900"
            >
              <component
                :is="TagIcon"
                :class="[
                  ['tags', 'create-tag', 'show-tag'].includes(route.name)
                    ? 'text-gray-500'
                    : 'text-gray-400 group-hover:text-gray-500',
                  'mr-3 flex-shrink-0 h-6 w-6',
                ]"
                aria-hidden="true"
              />
              {{ trans.tags }}
            </AppLink>
            <AppLink
              :to="{ name: 'topics' }"
              class="group flex items-center px-2 py-2 text-sm font-medium rounded-md"
              active-class="bg-gray-200 text-gray-900"
              inactive-class="text-gray-600 hover:bg-gray-50 hover:text-gray-900"
            >
              <component
                :is="RectangleStackIcon"
                :class="[
                  ['topics', 'create-topic', 'show-topic'].includes(route.name)
                    ? 'text-gray-500'
                    : 'text-gray-400 group-hover:text-gray-500',
                  'mr-3 flex-shrink-0 h-6 w-6',
                ]"
                aria-hidden="true"
              />
              {{ trans.topics }}
            </AppLink>
          </div>

          <hr class="my-5 border-t border-gray-200" aria-hidden="true" />

          <div class="flex-1 space-y-1 px-2">
            <AppLink
              :to="{ name: 'settings' }"
              class="group flex items-center px-2 py-2 text-sm font-medium rounded-md"
              active-class="bg-gray-200 text-gray-900"
              inactive-class="text-gray-600 hover:bg-gray-50 hover:text-gray-900"
            >
              <component
                :is="CogIcon"
                :class="[
                  ['settings'].includes(route.name)
                    ? 'text-gray-500'
                    : 'text-gray-400 group-hover:text-gray-500',
                  'mr-3 flex-shrink-0 h-6 w-6',
                ]"
                aria-hidden="true"
              />
              {{ trans.settings }}
            </AppLink>

            <a
              class="group flex items-center rounded-md px-2 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 cursor-pointer"
              @click="logout"
            >
              <component
                :is="ArrowRightOnRectangleIcon"
                class="mr-3 h-6 w-6 flex-shrink-0 text-gray-400 group-hover:text-gray-500"
                aria-hidden="true"
              />
              {{ trans.sign_out }}
            </a>
          </div>
        </nav>
      </div>
      <div class="flex flex-shrink-0 border-t border-gray-200 p-4">
        <AppLink
          :to="{
            name: 'show-user',
            params: { id: user.id },
          }"
          class="group block w-full flex-shrink-0"
        >
          <div class="flex items-center">
            <div>
              <img
                class="inline-block h-9 w-9 rounded-full"
                :src="user.avatar || user.default_avatar"
                alt=""
              />
            </div>
            <div class="ml-3">
              <p
                class="text-sm font-medium text-gray-700 group-hover:text-gray-900"
              >
                {{ user.name }}
              </p>
              <p
                class="text-xs font-medium text-gray-500 group-hover:text-gray-700"
              >
                {{ trans.view_profile }}
              </p>
            </div>
          </div>
        </AppLink>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, defineEmits } from 'vue'
import {
  ArrowRightOnRectangleIcon,
  TagIcon,
  CogIcon,
  BookmarkSquareIcon,
  HomeIcon,
  RectangleStackIcon,
  UserGroupIcon,
} from '@heroicons/vue/24/outline'
import { useStore } from 'vuex'
import AppLink from '@/components/AppLink'
import { useRoute } from 'vue-router'

defineProps({
  open: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['logout'])
const route = useRoute()
const store = useStore()
const user = computed(() => store.state.config.user)
const trans = computed(() => store.getters['config/trans'])

function logout() {
  emit('logout')
}
</script>
