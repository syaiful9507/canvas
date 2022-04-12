<template>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
      <div class="py-10">
        <main>
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <nav class="flex pb-5" aria-label="Breadcrumb">
              <ol role="list" class="flex items-center space-x-4">
                <li>
                  <div class="flex items-center">
                    <AppLink
                      :to="{ name: 'dashboard' }"
                      class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700"
                    >
                      {{ trans.dashboard }}
                    </AppLink>
                  </div>
                </li>
                <li>
                  <div class="flex items-center">
                    <svg
                      class="flex-shrink-0 h-5 w-5 text-gray-300"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                      aria-hidden="true"
                    >
                      <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                    </svg>
                    <AppLink
                      :to="{ name: 'posts' }"
                      class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700"
                    >
                      {{ trans.posts }}
                    </AppLink>
                  </div>
                </li>
              </ol>

              <AppLink
                :to="{ name: 'create-post' }"
                class="inline-flex items-center ml-auto mr-4 text-sm font-medium text-gray-500 hover:text-gray-700"
              >
                <PlusIcon class="-ml-1 mr-2 h-5 w-5" aria-hidden="true" />
                {{ trans.new_post }}
              </AppLink>
            </nav>

            <div
              :key="$route.fullPath"
              class="bg-white shadow sm:rounded-md"
            >
              <div v-if="results">



                <nav
                  v-if="results"
                  class="bg-white px-4 py-3 flex items-center justify-between border-b border-gray-200 sm:px-6"
                  aria-label="Pagination"
                >
                  <div class="flex space-x-4">
                    <AppLink
                      :to="{ name: 'posts', query: { type: 'published' } }"
                      class="rounded-md py-2 px-3 inline-flex items-center text-sm font-medium"
                      active-class="bg-gray-100 text-gray-900"
                      inactive-class="text-gray-900 hover:bg-gray-50 hover:text-gray-900"
                    >
                      Published
                    </AppLink>
                    <AppLink
                      :to="{ name: 'posts', query: { type: 'draft' } }"
                      class="rounded-md py-2 px-3 inline-flex items-center text-sm font-medium"
                      active-class="bg-gray-100 text-gray-900"
                      inactive-class="text-gray-900 hover:bg-gray-50 hover:text-gray-900"
                    >
                      Draft
                    </AppLink>
                    <Menu as="div" class="relative inline-block text-left">
                      <div>
                        <MenuButton class="inline-flex justify-center w-full rounded-md px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500">
                          Author
                          <ChevronDownIcon class="-mr-1 ml-2 h-5 w-5" aria-hidden="true" />
                        </MenuButton>
                      </div>

                      <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                        <MenuItems class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                          <div class="py-1">
                            <MenuItem v-for="user in results.users" :key="user.id" as="div">
                              <AppLink
                                :to="{
                                  name: 'users',
                                  query: { author: user.id },
                                }"
                                class="block m-auto px-4 py-2 text-sm"
                                inactive-class="text-gray-900 hover:bg-gray-50 hover:text-gray-900"
                                active-class="bg-gray-100 text-gray-900"
                              >
                              <div class="flex items-center">
                                  <div class="flex-shrink-0">
                                  <img
                                    class="h-6 w-6 rounded-full"
                                    :src="user.avatar || user.default_avatar"
                                    :alt="user.name"
                                  />
                                </div>
                                <div class="ml-3">
                                  {{user.name}}
                                </div>
                              </div>
                                
                              </AppLink>
                              
                              <!-- <a href="#" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">{{ user.name }}</a> -->
                            </MenuItem>
                          </div>
                        </MenuItems>
                      </transition>
                    </Menu>
                  </div>
                </nav>


                
              <ul role="list" class="divide-y divide-gray-200">
                <li v-for="post in results.posts.data" :key="post.id">
                  <AppLink
                    :to="{
                      name: 'show-post',
                      params: { id: post.id },
                    }"
                    class="block hover:bg-gray-50 cursor-pointer"
                  >
                    <div class="flex items-center px-4 py-4 sm:px-6">
                      <div class="min-w-0 flex-1 flex items-center">
                        <div class="flex-shrink-0">
                          <img
                            v-if="post.featured_image"
                            class="h-12 w-12 rounded-full"
                            :src="post.featured_image"
                            :alt="post.title"
                          />
                          <div v-else class="h-12 w-12 inline-flex justify-center items-center rounded-full bg-gray-100">
                            <CameraIcon
                              class="h-6 w-6 text-gray-400"
                              aria-hidden="true"
                            />
                          </div>
                        </div>
                        <div
                          class="min-w-0 flex-1 px-4 md:grid md:grid-cols-2 md:gap-4"
                        >
                          <div>
                            <p
                              class="text-sm font-medium text-indigo-600 truncate"
                            >
                              {{ post.title }}
                            </p>
                            <p
                              class="mt-2 flex items-center text-sm text-gray-500"
                            >
                              <span>{{ post.created_at }}</span>
                            </p>
                          </div>
                        </div>
                      </div>
                      <div>
                        <ChevronRightIcon
                          class="h-5 w-5 text-gray-400"
                          aria-hidden="true"
                        />
                      </div>
                    </div>
                  </AppLink>
                </li>
              </ul>

              <nav
                v-if="results"
                class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6"
                aria-label="Pagination"
              >
                <div class="hidden sm:block">
                  <p class="text-sm text-gray-700">
                    Showing
                    {{ ' ' }}
                    <span class="font-medium">{{ results.posts.from }}</span>
                    {{ ' ' }}
                    to
                    {{ ' ' }}
                    <span class="font-medium">{{ results.posts.to }}</span>
                    {{ ' ' }}
                    of
                    {{ ' ' }}
                    <span class="font-medium">{{ results.posts.total }}</span>
                    {{ ' ' }}
                    results
                  </p>
                </div>
                <div class="flex-1 flex justify-between sm:justify-end">
                  <AppLink
                    v-if="!!results.posts.prev_page_url"
                    :to="{
                      name: 'users',
                      query: { page: results.posts.current_page - 1 },
                    }"
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                    @click="fetchPosts(results.posts.current_page - 1)"
                  >
                    Previous
                  </AppLink>
                  <AppLink
                    v-if="!!results.posts.next_page_url"
                    :to="{
                      name: 'users',
                      query: { page: results.posts.current_page + 1 },
                    }"
                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                    @click="fetchPosts(results.posts.current_page + 1)"
                  >
                    Next
                  </AppLink>
                </div>
              </nav>
              </div>
            
              <div v-else class="text-center py-8">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="mx-auto h-12 w-12 text-gray-400"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  stroke-width="1"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                  />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">
                  {{ trans.no_published_posts }}
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                  {{ trans.get_started_by_creating_a_new_post }}
                </p>
                <div class="mt-6">
                  <button
                    type="button"
                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    <PlusIcon class="-ml-1 mr-2 h-5 w-5" aria-hidden="true" />
                    {{ trans.new_post }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, watchEffect } from 'vue'
import {
  Menu,
  MenuButton,
  MenuItem,
  MenuItems,
} from '@headlessui/vue'
import { useStore } from 'vuex'
import AppLink from '@/components/AppLink'
import { ChevronRightIcon, PlusIcon, CameraIcon, ChevronDownIcon } from '@heroicons/vue/solid'
import request from '@/utils/request'

const store = useStore()
const trans = computed(() => store.getters['config/trans'])
const results = ref(null)

watchEffect(async () => {
  await fetchPosts()
})

function fetchPosts(page) {
  return request
    .get('api/posts', { params: { page: page } })
    .then(({ data }) => {
      results.value = data
    })
}
</script>
