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
                    <p class="ml-4 text-sm font-medium text-gray-500">
                      {{ trans.users }}
                    </p>
                  </div>
                </li>
              </ol>

              <AppLink
                :to="{ name: 'create-user' }"
                class="inline-flex items-center ml-auto mr-4 text-sm font-medium text-gray-500 hover:text-gray-700"
              >
                <PlusIcon class="-ml-1 mr-2 h-5 w-5" aria-hidden="true" />
                {{ trans.new_user }}
              </AppLink>
            </nav>

            <div class="bg-white shadow rounded-md">
              <ul v-if="results" role="list" class="divide-y divide-gray-200">
                <li v-for="user in results.data" :key="user.id">
                  <AppLink
                    :to="{
                      name: 'show-user',
                      params: { id: user.id },
                    }"
                    class="block hover:bg-gray-50 cursor-pointer"
                  >
                    <div class="flex items-center px-4 py-4 sm:px-6">
                      <div class="min-w-0 flex-1 flex items-center">
                        <div class="flex-shrink-0">
                          <img
                            class="h-12 w-12 rounded-full"
                            :src="user.avatar || user.default_avatar"
                            :alt="user.name"
                          />
                        </div>
                        <div
                          class="min-w-0 flex-1 px-4 md:grid md:grid-cols-2 md:gap-4"
                        >
                          <div>
                            <p
                              class="text-sm font-medium text-indigo-600 truncate"
                            >
                              {{ user.name }}
                            </p>
                            <p
                              class="mt-2 flex items-center text-sm text-gray-500"
                            >
                              <span class="truncate">{{ user.email }}</span>
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
                        name: 'users',
                        query: { page: results.current_page - 1 },
                      }"
                      class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                      @click="fetchUsers(results.current_page - 1)"
                    >
                      Previous
                    </AppLink>
                    <AppLink
                      v-if="!!results.next_page_url"
                      :to="{
                        name: 'users',
                        query: { page: results.current_page + 1 },
                      }"
                      class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                      @click="fetchUsers(results.current_page + 1)"
                    >
                      Next
                    </AppLink>
                  </div>
                </div>
              </nav>
            </div>
          </div>
        </main>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, reactive, defineProps, watchEffect } from 'vue'
import { useStore } from 'vuex'
import AppLink from '@/components/AppLink'
import { ChevronRightIcon, PlusIcon } from '@heroicons/vue/solid'
import request from '@/utils/request'

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
const results = ref(null)
const query = reactive({
  page: props.page || 1,
})

watchEffect(async () => {
  await fetchUsers()
})

function fetchUsers(page) {
  return request
    .get('api/users', {
      params: {
        ...(query.page > 1 && { page: query.page }),
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
