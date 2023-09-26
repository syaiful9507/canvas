<template>
  <div class="py-6">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 md:px-8">
      <div v-if="topic">
        <div>
          <nav class="sm:hidden" :aria-label="trans.back">
            <AppLink
              :to="{ name: 'topics' }"
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
                  <AppLink
                    :to="{ name: 'topics' }"
                    class="ml-4 text-sm font-medium text-gray-500"
                  >
                    {{ trans.topics }}
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
                    class="ml-4 text-sm font-medium text-gray-500"
                  >
                    {{ topic.name }}
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
              {{ topic.name }}
            </h2>
          </div>
        </div>
      </div>

      <div class="py-4">
        <div class="h-96 rounded-lg border-4 border-dashed border-gray-200" />
      </div>
    </div>
  </div>
</template>

<script setup>
import AppLink from '@/components/AppLink'
import request from '@/utils/request'
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/solid'
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import { useStore } from 'vuex'

const store = useStore()
const route = useRoute()
const trans = computed(() => store.getters['config/trans'])
const topic = ref(null)

onMounted(async () => {
  await fetchTopic()
})

function fetchTopic() {
  return request.get(`api/topics/${route.params.id}`).then(({ data }) => {
    topic.value = data
  })
}
</script>
