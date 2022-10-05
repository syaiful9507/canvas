<template>
  <div class="py-6">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 md:px-8">
      <div>
        <div>
          <nav class="sm:hidden" :aria-label="trans.back">
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
          <nav class="hidden sm:flex" :aria-label="trans.breadcrumb">
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
                    {{ trans.tags }}
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
              {{ trans.tags }}
            </h2>
          </div>
          <div
            v-if="filteredItems?.length > 0"
            class="mt-4 flex flex-shrink-0 md:mt-0 md:ml-4"
          >
            <AppLink
              :to="{ name: 'create-tag' }"
              class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-3 py-2 text-sm font-medium leading-4 text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
              <PlusIcon class="-ml-0.5 mr-2 h-4 w-4" aria-hidden="true" />
              {{ trans.new_tag }}
            </AppLink>
          </div>
        </div>
      </div>

      <div class="py-4">
        <nav
          class="bg-white py-3 flex items-center justify-between border-b border-gray-200 rounded-t-md"
        >
          <div class="ml-auto flex space-x-4">
            <Menu as="div" class="relative inline-block text-left">
              <div>
                <MenuButton
                  class="inline-flex justify-center w-full rounded-md px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500"
                >
                  {{ usageLabel }}
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
                          name: 'tags',
                        }"
                        :class="
                          !$route.query?.usage
                            ? 'bg-gray-100 text-gray-900'
                            : 'text-gray-900 hover:bg-gray-50 hover:text-gray-900'
                        "
                        class="block m-auto px-4 py-2 text-sm"
                      >
                        <div class="flex items-center">
                          {{ trans.everything }}
                        </div>
                      </AppLink>
                    </MenuItem>
                    <MenuItem as="div">
                      <AppLink
                        :to="{
                          name: 'tags',
                          query: {
                            usage: popular,
                            ...(query.sort && { sort: query.sort }),
                          },
                        }"
                        :class="
                          $route.query?.usage === popular
                            ? 'bg-gray-100 text-gray-900'
                            : 'text-gray-900 hover:bg-gray-50 hover:text-gray-900'
                        "
                        class="block m-auto px-4 py-2 text-sm"
                      >
                        <div class="flex items-center">
                          {{ trans.most_popular }}
                        </div>
                      </AppLink>
                    </MenuItem>
                    <MenuItem as="div">
                      <AppLink
                        :to="{
                          name: 'tags',
                          query: {
                            usage: unpopular,
                            ...(query.sort && { sort: query.sort }),
                          },
                        }"
                        :class="
                          $route.query?.usage === unpopular
                            ? 'bg-gray-100 text-gray-900'
                            : 'text-gray-900 hover:bg-gray-50 hover:text-gray-900'
                        "
                        class="block m-auto px-4 py-2 text-sm"
                      >
                        <div class="flex items-center">
                          {{ trans.least_popular }}
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
                          name: 'tags',
                          query: {
                            sort: descending,
                            ...(query.usage && { usage: query.usage }),
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
                          name: 'tags',
                          query: {
                            sort: ascending,
                            ...(query.usage && { usage: query.usage }),
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
            <li v-for="tag in filteredItems" :key="tag.id">
              <AppLink
                :to="{
                  name: 'show-tag',
                  params: { id: tag.id },
                }"
                class="block hover:bg-gray-50 cursor-pointer"
              >
                <div class="flex items-center py-4 px-3">
                  <div class="min-w-0 flex-1 flex items-center">
                    <div
                      class="min-w-0 flex-1 px-4 md:grid md:grid-cols-2 md:gap-4"
                    >
                      <div>
                        <p class="text-sm font-medium text-indigo-600 truncate">
                          {{ tag.name }}
                          <span
                            v-if="!!tag.posts_count"
                            class="inline-flex items-center px-2.5 py-0.5 ml-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800"
                            >{{ tag.posts_count }}</span
                          >
                        </p>
                        <p class="mt-2 flex items-center text-sm text-gray-500">
                          <span>{{ dateFromNow(tag.created_at, locale) }}</span>
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
            :aria-label="trans.pagination"
          >
            <div class="sm:block">
              <p class="text-sm text-gray-700">
                {{ trans.showing }} {{ ' '
                }}<span class="font-medium">{{ results.from }}</span
                >{{ ' ' }} {{ trans.to }} {{ ' '
                }}<span class="font-medium">{{ results.to }}</span
                >{{ ' ' }} {{ trans.of }} {{ ' '
                }}<span class="font-medium">{{ results.total }}</span
                >{{ ' ' }} {{ trans.results }}
              </p>
            </div>
            <div class="ml-auto">
              <div class="flex-1 flex justify-between sm:justify-end">
                <AppLink
                  v-if="!!results.prev_page_url"
                  :to="{
                    name: 'tags',
                    query: {
                      page: results.current_page - 1,
                      page: results.current_page - 1,
                      ...(query.usage && { usage: query.usage }),
                      ...(query.sort && { sort: query.sort }),
                    },
                  }"
                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                  @click="decrementPage() && fetchTags()"
                >
                  {{ trans.previous }}
                </AppLink>
                <AppLink
                  v-if="!!results.next_page_url"
                  :to="{
                    name: 'tags',
                    query: {
                      page: results.current_page + 1,
                      ...(query.usage && { usage: query.usage }),
                      ...(query.sort && { sort: query.sort }),
                    },
                  }"
                  class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                  @click="incrementPage() && fetchTags()"
                >
                  {{ trans.next }}
                </AppLink>
              </div>
            </div>
          </nav>
        </div>

        <div v-if="filteredItems?.length === 0">
          <div class="py-24 text-center">
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
                d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"
              />
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M6 6h.008v.008H6V6z"
              />
            </svg>

            <h3 class="mt-2 text-sm font-medium text-gray-900">
              {{ trans.no_tags }}
            </h3>
            <p class="mt-1 text-sm text-gray-500">
              {{ trans.get_started_by_creating_a_new_tag }}
            </p>
            <div class="mt-6">
              <AppLink
                :to="{ name: 'create-tag' }"
                class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-3 py-2 text-sm font-medium leading-4 text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
              >
                <PlusIcon class="-ml-0.5 mr-2 h-4 w-4" aria-hidden="true" />
                {{ trans.new_tag }}
              </AppLink>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, reactive, ref, watchEffect } from 'vue'
import { useStore } from 'vuex'
import AppLink from '@/components/AppLink'
import {
  ChevronRightIcon,
  ChevronLeftIcon,
  PlusIcon,
  ChevronDownIcon,
} from '@heroicons/vue/24/solid'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import request from '@/utils/request'
import dateFromNow from '@/utils/dateFromNow'

const props = defineProps({
  page: {
    type: String,
    default: '',
  },
  sort: {
    type: String,
    default: '',
  },
  usage: {
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
const popular = ref('popular')
const unpopular = ref('unpopular')
const query = reactive({
  page: props.page || 1,
  sort: props.sort || null,
  usage: props.usage || null,
})

const filteredItems = computed(() => {
  return results?.value?.data || []
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

const usageLabel = computed(() => {
  switch (query.usage) {
    case popular.value:
      return trans.value.most_popular
    case unpopular.value:
      return trans.value.least_popular
    default:
      return trans.value.usage
  }
})

watchEffect(async () => {
  await fetchTags()
})

function fetchTags() {
  return request
    .get('api/tags', {
      params: {
        ...(query.page > 1 && { page: query.page }),
        ...(query.sort && { sort: query.sort }),
        ...(query.usage && { usage: query.usage }),
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
