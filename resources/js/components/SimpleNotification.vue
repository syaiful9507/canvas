<template>
  <!-- Global notification live region, render this permanently at the end of the document -->
  <div
    aria-live="assertive"
    class="pointer-events-none absolute z-10 inset-0 flex items-end px-4 py-6 sm:items-start sm:p-6"
  >
    <div class="flex w-full flex-col items-center space-y-4 sm:items-end">
      <!-- Notification panel, dynamically insert this into the live region when it needs to be displayed -->
      <transition
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          v-if="show"
          class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white dark:bg-gray-900 shadow-lg ring-1 ring-black ring-opacity-5"
        >
          <div class="p-4">
            <div class="flex items-start">
              <div class="flex-shrink-0">
                <component
                  :is="icon"
                  class="h-6 w-6 text-gray-400 dark:text-gray-300"
                  aria-hidden="true"
                />
              </div>
              <div class="ml-3 w-0 flex-1 pt-0.5">
                <p class="text-sm font-medium text-gray-900 dark:text-white">
                  {{ title }}
                </p>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">
                  {{ description }}
                </p>
                <div v-if="url" class="mt-3 flex space-x-7">
                  <a
                    :href="url"
                    target="_blank"
                    class="inline-flex rounded-md bg-white dark:bg-gray-900 text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-500 dark:hover:text-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >{{ trans.learn_more }}
                    <ArrowTopRightOnSquareIcon
                      class="self-center h-5 w-5 pl-1"
                      aria-hidden="true"
                    />
                  </a>
                  <button
                    type="button"
                    class="rounded-md bg-white text-sm font-medium text-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:text-white hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    @click="show = false"
                  >
                    {{ trans.dismiss }}
                  </button>
                </div>
              </div>
              <div class="ml-4 flex flex-shrink-0">
                <button
                  type="button"
                  class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500 dark:bg-gray-900 dark:text-gray-300 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                  @click="show = false"
                >
                  <span class="sr-only">{{ trans.close }}</span>
                  <XMarkIcon class="h-5 w-5" aria-hidden="true" />
                </button>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script setup>
import { ArrowTopRightOnSquareIcon } from '@heroicons/vue/20/solid'
import { XMarkIcon } from '@heroicons/vue/20/solid'
import {
  ArrowUpCircleIcon,
  BookmarkSquareIcon,
  RectangleStackIcon,
  TagIcon,
  UserIcon
} from '@heroicons/vue/24/outline'
import { computed, ref } from 'vue'
import { useStore } from 'vuex'

let props = defineProps({
  type: {
    type: String,
    default: ''
  },
  title: {
    type: String,
    default: ''
  },
  description: {
    type: String,
    default: ''
  },
  url: {
    type: String,
    default: ''
  }
})

const store = useStore()
const show = ref(true)
const trans = computed(() => store.getters['config/trans'])
const icon = computed(() => {
  switch (props.type) {
    case 'update':
      return ArrowUpCircleIcon
    case 'post':
      return BookmarkSquareIcon
    case 'tag':
      return TagIcon
    case 'topic':
      return RectangleStackIcon
    case 'user':
      return UserIcon
    default:
      return ''
  }
})

setTimeout(function () {
  show.value = false
}, 5000)
</script>
