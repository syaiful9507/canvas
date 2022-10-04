<template>
  <div class="py-6">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 md:px-8">
      <div>
        <div>
          <nav class="sm:hidden" aria-label="Back">
            <AppLink
              :to="{ name: 'dashboard' }"
              class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700"
            >
              <ChevronLeftIcon
                class="-ml-1 mr-1 h-5 w-5 flex-shrink-0 text-gray-400"
                aria-hidden="true"
              />
              {{ trans.back }}
            </AppLink>
          </nav>
          <nav class="hidden sm:flex" aria-label="Breadcrumb">
            <ol role="list" class="flex items-center space-x-4">
              <li>
                <div class="flex">
                  <AppLink
                    :to="{ name: 'dashboard' }"
                    class="text-sm font-medium text-gray-500 hover:text-gray-700"
                  >
                    {{ trans.dashboard }}
                  </AppLink>
                </div>
              </li>
              <li>
                <div class="flex items-center">
                  <ChevronRightIcon
                    class="h-5 w-5 flex-shrink-0 text-gray-400"
                    aria-hidden="true"
                  />
                  <p
                    aria-current="page"
                    class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700"
                  >
                    {{ trans.posts }}
                  </p>
                </div>
              </li>
            </ol>
          </nav>
        </div>
        <div class="mt-2 md:flex md:items-center md:justify-between">
          <div class="min-w-0 flex-1">
            <h2
              class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight"
            >
              {{ trans.posts }}
            </h2>
          </div>
          <div
            v-if="filteredItems?.length > 0 || isSearching"
            class="mt-4 flex flex-shrink-0 md:mt-0 md:ml-4"
          >
            <AppLink
              :to="{ name: 'create-post' }"
              class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-3 py-2 text-sm font-medium leading-4 text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
              <PlusIcon class="-ml-0.5 mr-2 h-4 w-4" aria-hidden="true" />
              {{ trans.new_post }}
            </AppLink>
          </div>
        </div>
      </div>

      <div class="py-4">
        <nav
          class="bg-white py-3 flex items-center justify-between border-b border-gray-200 rounded-t-md"
        >
          <div class="flex space-x-4">
            <AppLink
              :to="{
                name: 'posts',
                query: {
                  ...(query.author && { author: query.author }),
                  ...(query.sort && { sort: query.sort }),
                  type: 'published',
                },
              }"
              class="rounded-md py-2 px-3 inline-flex items-center text-sm font-medium"
              :class="
                $route.query?.type !== 'draft'
                  ? 'bg-gray-100 text-gray-900'
                  : 'text-gray-900 hover:bg-gray-50 hover:text-gray-900'
              "
            >
              {{ trans.published }}
            </AppLink>
            <AppLink
              :to="{
                name: 'posts',
                query: {
                  ...(query.author && { author: query.author }),
                  ...(query.sort && { sort: query.sort }),
                  type: 'draft',
                },
              }"
              class="rounded-md py-2 px-3 inline-flex items-center text-sm font-medium"
              :class="
                $route.query?.type === 'draft'
                  ? 'bg-gray-100 text-gray-900'
                  : 'text-gray-900 hover:bg-gray-50 hover:text-gray-900'
              "
            >
              {{ trans.draft }}
            </AppLink>
          </div>
          <div class="ml-auto flex space-x-4">
            <Menu as="div" class="relative inline-block text-left">
              <div>
                <MenuButton
                  class="inline-flex justify-center w-full rounded-md px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500"
                >
                  {{ authorLabel }}
                  <ChevronDownIcon
                    class="-mr-1 ml-2 h-5 w-5"
                    aria-hidden="true"
                  />
                </MenuButton>
              </div>

              <transition
                enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
              >
                <MenuItems
                  class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                >
                  <div class="py-1">
                    <MenuItem
                      v-for="user in results.users"
                      :key="user.id"
                      as="div"
                    >
                      <AppLink
                        :to="{
                          name: 'posts',
                          query: {
                            author: user.id,
                            ...(query.type && { type: query.type }),
                            ...(query.sort && { sort: query.sort }),
                          },
                        }"
                        :class="
                          $route.query?.author === user.id
                            ? 'bg-gray-100 text-gray-900'
                            : 'text-gray-900 hover:bg-gray-50 hover:text-gray-900'
                        "
                        class="block m-auto px-4 py-2 text-sm"
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
                            {{ user.name }}
                          </div>
                        </div>
                      </AppLink>
                    </MenuItem>
                  </div>
                </MenuItems>
              </transition>
            </Menu>

            <Menu as="div" class="relative inline-block text-left">
              <div>
                <MenuButton
                  class="inline-flex justify-center w-full rounded-md px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500"
                >
                  {{ sortLabel }}
                  <ChevronDownIcon
                    class="-mr-1 ml-2 h-5 w-5"
                    aria-hidden="true"
                  />
                </MenuButton>
              </div>

              <transition
                enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
              >
                <MenuItems
                  class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                >
                  <div class="py-1">
                    <MenuItem as="div">
                      <AppLink
                        :to="{
                          name: 'posts',
                          query: {
                            sort: descending,
                            ...(query.type && { type: query.type }),
                            ...(query.author && {
                              author: query.author,
                            }),
                          },
                        }"
                        :class="
                          $route.query?.sort === descending ||
                          !$route.query.sort
                            ? 'bg-gray-100 text-gray-900'
                            : 'text-gray-900 hover:bg-gray-50 hover:text-gray-900'
                        "
                        class="block m-auto px-4 py-2 text-sm"
                      >
                        <div class="flex items-center">{{ trans.newest }}</div>
                      </AppLink>
                    </MenuItem>
                    <MenuItem as="div">
                      <AppLink
                        :to="{
                          name: 'posts',
                          query: {
                            sort: ascending,
                            ...(query.type && { type: query.type }),
                            ...(query.author && {
                              author: query.author,
                            }),
                          },
                        }"
                        :class="
                          $route.query?.sort === ascending
                            ? 'bg-gray-100 text-gray-900'
                            : 'text-gray-900 hover:bg-gray-50 hover:text-gray-900'
                        "
                        class="block m-auto px-4 py-2 text-sm"
                      >
                        <div class="flex items-center">{{ trans.oldest }}</div>
                      </AppLink>
                    </MenuItem>
                  </div>
                </MenuItems>
              </transition>
            </Menu>
          </div>
        </nav>

        <div v-if="filteredItems?.length">
          <ul role="list" class="divide-y divide-gray-200">
            <li v-for="post in filteredItems" :key="post.id">
              <AppLink
                :to="{
                  name: 'show-post',
                  params: { id: post.id },
                }"
                class="block hover:bg-gray-50 cursor-pointer"
              >
                <div class="flex items-center py-4 px-3">
                  <div class="min-w-0 flex-1 flex items-center">
                    <div class="flex-shrink-0">
                      <img
                        v-if="post.featured_image"
                        class="h-12 w-12 rounded-full"
                        :src="post.featured_image"
                        :alt="post.title"
                      />
                      <div
                        v-else
                        class="h-12 w-12 inline-flex justify-center items-center rounded-full bg-gray-100"
                      >
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
                        <p class="text-sm font-medium text-indigo-600 truncate">
                          {{ post.title }}
                        </p>
                        <p class="mt-2 flex items-center text-sm text-gray-500">
                          <span>{{
                            dateFromNow(post.created_at, locale)
                          }}</span>
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
            class="bg-white py-3 flex items-center justify-between border-t border-gray-200 rounded-b-md"
            aria-label="Pagination"
          >
            <div class="sm:block">
              <p class="text-sm text-gray-700">
                {{ trans.showing }} {{ ' '
                }}<span class="font-medium">{{ results.posts.from }}</span
                >{{ ' ' }} {{ trans.to }} {{ ' '
                }}<span class="font-medium">{{ results.posts.to }}</span
                >{{ ' ' }} {{ trans.of }} {{ ' '
                }}<span class="font-medium">{{ results.posts.total }}</span
                >{{ ' ' }} {{ trans.results }}
              </p>
            </div>
            <div class="ml-auto">
              <div class="flex-1 flex justify-between sm:justify-end">
                <AppLink
                  v-if="!!results.posts.prev_page_url"
                  :to="{
                    name: 'posts',
                    query: {
                      page: results.posts.current_page - 1,
                      ...(query.author && { author: query.author }),
                      ...(query.type && { type: query.type }),
                      ...(query.sort && { sort: query.sort }),
                    },
                  }"
                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                  @click="decrementPage() && fetchPosts()"
                >
                  {{ trans.previous }}
                </AppLink>
                <AppLink
                  v-if="!!results.posts.next_page_url"
                  :to="{
                    name: 'posts',
                    query: {
                      page: results.posts.current_page + 1,
                      ...(query.author && { author: query.author }),
                      ...(query.type && { type: query.type }),
                      ...(query.sort && { sort: query.sort }),
                    },
                  }"
                  class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                  @click="incrementPage() && fetchPosts()"
                >
                  {{ trans.next }}
                </AppLink>
              </div>
            </div>
          </nav>
        </div>

        <div v-if="filteredItems?.length === 0">
          <div
            v-if="isSearching"
            class="py-24 px-6 text-center text-sm sm:px-14"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1"
              stroke="currentColor"
              class="mx-auto h-12 w-12 text-gray-400"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"
              />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">
              {{ trans.no_results_found }}
            </h3>
            <p class="mt-1 text-sm font-semibold text-indigo-500">
              <AppLink
                :to="{ name: 'posts' }"
                class="mt-1 text-sm font-semibold text-indigo-500"
              >
                {{ trans.clear_current_search_filters_and_sorts }}
              </AppLink>
            </p>
          </div>

          <div v-else class="py-24 text-center">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1"
              stroke="currentColor"
              class="mx-auto h-12 w-12 text-gray-400"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75h1.5m9 0h-9"
              />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">
              {{ trans.no_posts }}
            </h3>
            <p class="mt-1 text-sm text-gray-500">
              {{ trans.get_started_by_creating_a_new_post }}
            </p>
            <div class="mt-6">
              <AppLink
                :to="{ name: 'create-post' }"
                class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-3 py-2 text-sm font-medium leading-4 text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
              >
                <PlusIcon class="-ml-0.5 mr-2 h-4 w-4" aria-hidden="true" />
                {{ trans.new_post }}
              </AppLink>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, reactive, watchEffect } from 'vue'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { useStore } from 'vuex'
import AppLink from '@/components/AppLink'
import {
  ChevronRightIcon,
  ChevronLeftIcon,
  PlusIcon,
  CameraIcon,
  ChevronDownIcon,
} from '@heroicons/vue/24/solid'
import dateFromNow from '@/utils/dateFromNow'
import request from '@/utils/request'

const props = defineProps({
  author: {
    type: String,
    default: '',
  },
  page: {
    type: String,
    default: '',
  },
  sort: {
    type: String,
    default: '',
  },
  type: {
    type: String,
    default: '',
  },
})

const store = useStore()
const trans = computed(() => store.getters['config/trans'])
const locale = computed(() => store.getters['config/locale'])
const results = ref(null)
const ascending = ref('asc')
const descending = ref('desc')
const query = reactive({
  page: props.page || 1,
  type: props.type || null,
  author: props.author || null,
  sort: props.sort || null,
})

const isSearching = computed(() => {
  return (
    query.page > 1 ||
    query.type !== null ||
    query.author !== null ||
    query.sort !== null
  )
})

const filteredItems = computed(() => {
  return results?.value?.posts?.data || []
})

const sortLabel = computed(() => {
  switch (query.sort) {
    case descending.value:
      return trans.value.newest
    case ascending.value:
      return trans.value.oldest
    default:
      return trans.value.sort
  }
})

const authorLabel = computed(() => {
  let filteredAuthor = results?.value?.users.filter((item) => {
    return item.id === query.author
  })[0]

  return filteredAuthor ? filteredAuthor.name : trans.value.author
})

watchEffect(async () => {
  await fetchPosts()
})

function fetchPosts() {
  return request
    .get('api/posts', {
      params: {
        ...(query.page > 1 && { page: query.page }),
        ...(query.author && { author: query.author }),
        ...(query.type && { type: query.type }),
        ...(query.sort && { sort: query.sort }),
      },
    })
    .then(({ data }) => {
      results.value = data
    })
}

function incrementPage() {
  query.page++
}

function decrementPage() {
  query.page--
}
</script>
