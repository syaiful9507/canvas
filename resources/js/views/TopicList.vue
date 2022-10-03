<template>
  <div class="py-6">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 md:px-8">
      <h1 class="text-2xl font-semibold text-gray-900">
        {{ trans.topics }}
      </h1>
    </div>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 md:px-8">
      <div class="py-4">
        <nav class="flex pb-5" aria-label="Breadcrumb">
          <ol role="list" class="flex items-center space-x-4">
            <li>
              <div class="flex items-center">
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
                <svg
                  class="flex-shrink-0 h-5 w-5 text-gray-300"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                  aria-hidden="true"
                >
                  <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                </svg>
                <p class="ml-4 text-sm font-medium text-gray-500">
                  {{ trans.topics }}
                </p>
              </div>
            </li>
          </ol>

          <AppLink
            v-if="hasTopicsToDisplay"
            :to="{ name: 'create-topic' }"
            class="inline-flex items-center ml-auto mr-4 text-sm font-medium text-gray-500 hover:text-gray-700"
          >
            <PlusIcon class="-ml-1 mr-2 h-5 w-5" aria-hidden="true" />
            {{ trans.new_topic }}
          </AppLink>
        </nav>

        <div v-if="hasTopicsToDisplay">
          <nav
            class="bg-white px-4 py-3 flex items-center justify-between border-b border-gray-200 sm:px-6 rounded-t-md"
          >
            <div class="flex space-x-4">
              <span
                class="py-2 px-3 inline-flex items-center text-sm font-medium text-gray-900"
              >
                <!-- TODO: Pluralize and localize -->
                {{ results.total }} Topics
              </span>
            </div>

            <Menu as="div" class="relative inline-block text-left">
              <div>
                <MenuButton
                  class="inline-flex justify-center w-full rounded-md px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500"
                >
                  Sort
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
                          name: 'topics',
                          query: { sort: descending },
                        }"
                        :class="
                          $route.query?.sort === descending ||
                          !$route.query.sort
                            ? 'bg-gray-100 text-gray-900'
                            : 'text-gray-900 hover:bg-gray-50 hover:text-gray-900'
                        "
                        class="block m-auto px-4 py-2 text-sm"
                      >
                        <div class="flex items-center">Newest</div>
                      </AppLink>
                    </MenuItem>
                    <MenuItem as="div">
                      <AppLink
                        :to="{
                          name: 'topics',
                          query: { sort: ascending },
                        }"
                        :class="
                          $route.query?.sort === ascending
                            ? 'bg-gray-100 text-gray-900'
                            : 'text-gray-900 hover:bg-gray-50 hover:text-gray-900'
                        "
                        class="block m-auto px-4 py-2 text-sm"
                      >
                        <div class="flex items-center">Oldest</div>
                      </AppLink>
                    </MenuItem>
                  </div>
                </MenuItems>
              </transition>
            </Menu>
          </nav>
          <ul role="list" class="divide-y divide-gray-200">
            <li v-for="topic in results.data" :key="topic.id">
              <AppLink
                :to="{
                  name: 'show-topic',
                  params: { id: topic.id },
                }"
                class="block hover:bg-gray-50 cursor-pointer"
              >
                <div class="flex items-center px-4 py-4 sm:px-6">
                  <div class="min-w-0 flex-1 flex items-center">
                    <div
                      class="min-w-0 flex-1 px-4 md:grid md:grid-cols-2 md:gap-4"
                    >
                      <div>
                        <p class="text-sm font-medium text-indigo-600 truncate">
                          {{ topic.name }}
                          <span
                            v-if="!!topic.posts_count"
                            class="inline-flex items-center px-2.5 py-0.5 ml-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800"
                            >{{ topic.posts_count }}</span
                          >
                        </p>
                        <p class="mt-2 flex items-center text-sm text-gray-500">
                          <span>{{
                            dateFromNow(topic.created_at, locale)
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
            class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 rounded-b-md"
            aria-label="Pagination"
          >
            <div class="sm:block">
              <p class="text-sm text-gray-700">
                Showing
                {{ ' ' }}
                <span class="font-medium">{{ results.from }}</span>
                {{ ' ' }}
                to
                {{ ' ' }}
                <span class="font-medium">{{ results.to }}</span>
                {{ ' ' }}
                of
                {{ ' ' }}
                <span class="font-medium">{{ results.total }}</span>
                {{ ' ' }}
                results
              </p>
            </div>
            <div class="ml-auto">
              <div class="flex-1 flex justify-between sm:justify-end">
                <AppLink
                  v-if="!!results.prev_page_url"
                  :to="{
                    name: 'topics',
                    query: {
                      page: results.current_page - 1,
                      ...(query.sort && { sort: query.sort }),
                    },
                  }"
                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                  @click="decrementPage() && fetchTopics()"
                >
                  Previous
                </AppLink>
                <AppLink
                  v-if="!!results.next_page_url"
                  :to="{
                    name: 'topics',
                    query: {
                      page: results.current_page + 1,
                      ...(query.sort && { sort: query.sort }),
                    },
                  }"
                  class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                  @click="incrementPage() && fetchTopics()"
                >
                  Next
                </AppLink>
              </div>
            </div>
          </nav>
        </div>

        <div v-else class="py-24 text-center">
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
            {{ trans.no_topics }}
          </h3>
          <p class="mt-1 text-sm text-gray-500">
            {{ trans.get_started_by_creating_a_new_topic }}
          </p>
          <div class="mt-6">
            <button
              type="button"
              class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              <PlusIcon class="-ml-1 mr-2 h-5 w-5" aria-hidden="true" />
              {{ trans.new_topic }}
            </button>
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
})

const store = useStore()
const trans = computed(() => store.getters['config/trans'])
const locale = computed(() => store.getters['config/locale'])
const results = ref(null)
const ascending = ref('asc')
const descending = ref('desc')
const query = reactive({
  page: props.page || 1,
  sort: props.sort || null,
})

const hasTopicsToDisplay = computed(() => {
  return results.value?.data?.length > 0
})

watchEffect(async () => {
  await fetchTopics()
})

function fetchTopics() {
  return request
    .get('api/topics', {
      params: {
        ...(query.page > 1 && { page: query.page }),
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
