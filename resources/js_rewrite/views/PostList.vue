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
            </nav>

            <div class="bg-white shadow overflow-hidden sm:rounded-md">
              <ul
                v-if="posts.length"
                role="list"
                class="divide-y divide-gray-200"
              >
                <li v-for="post in posts" :key="post.id">
                  <a :href="post.href" class="block hover:bg-gray-50">
                    <div class="flex items-center px-4 py-4 sm:px-6">
                      <div class="min-w-0 flex-1 flex items-center">
                        <div class="flex-shrink-0">
                          <img
                            class="h-12 w-12 rounded-full"
                            :src="post.imageUrl"
                            alt=""
                          />
                        </div>
                        <div
                          class="min-w-0 flex-1 px-4 md:grid md:grid-cols-2 md:gap-4"
                        >
                          <div>
                            <p
                              class="text-sm font-medium text-indigo-600 truncate"
                            >
                              {{ post.name }}
                            </p>
                            <p
                              class="mt-2 flex items-center text-sm text-gray-500"
                            >
                              <MailIcon
                                class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                                aria-hidden="true"
                              />
                              <span class="truncate">{{ post.email }}</span>
                            </p>
                          </div>
                          <div class="hidden md:block">
                            <div>
                              <p class="text-sm text-gray-900">
                                Applied on
                                {{ ' ' }}
                                <time :datetime="post.date">{{
                                  post.dateFull
                                }}</time>
                              </p>
                              <p
                                class="mt-2 flex items-center text-sm text-gray-500"
                              >
                                <CheckCircleIcon
                                  class="flex-shrink-0 mr-1.5 h-5 w-5 text-green-400"
                                  aria-hidden="true"
                                />
                                {{ post.stage }}
                              </p>
                            </div>
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
                  </a>
                </li>
              </ul>

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
import { computed, onMounted } from 'vue'
import { useStore } from 'vuex'
import AppLink from '@/components/AppLink'
import request from '@/request'
import {
  CheckCircleIcon,
  ChevronRightIcon,
  MailIcon,
  PlusIcon,
} from '@heroicons/vue/solid'

const store = useStore()
const trans = computed(() => store.getters['config/trans'])
const posts = []

onMounted(() => {
  // todo: fetch posts
})
</script>
