<template>
  <TransitionRoot as="template" :show="open">
    <Dialog as="div" class="relative z-40 md:hidden" @close="closeSidebar">
      <TransitionChild
        as="template"
        enter="transition-opacity ease-linear duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="transition-opacity ease-linear duration-300"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75" />
      </TransitionChild>

      <div class="fixed inset-0 z-40 flex">
        <TransitionChild
          as="template"
          enter="transition ease-in-out duration-300 transform"
          enter-from="-translate-x-full"
          enter-to="translate-x-0"
          leave="transition ease-in-out duration-300 transform"
          leave-from="translate-x-0"
          leave-to="-translate-x-full"
        >
          <DialogPanel
            class="relative flex w-full max-w-xs flex-1 flex-col bg-white dark:bg-gray-800"
          >
            <TransitionChild
              as="template"
              enter="ease-in-out duration-300"
              enter-from="opacity-0"
              enter-to="opacity-100"
              leave="ease-in-out duration-300"
              leave-from="opacity-100"
              leave-to="opacity-0"
            >
              <div class="absolute top-0 right-0 -mr-12 pt-2">
                <button
                  type="button"
                  class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                  @click="closeSidebar"
                >
                  <span class="sr-only">{{ trans.close_sidebar }}</span>
                  <XMarkIcon class="h-6 w-6 text-white" aria-hidden="true" />
                </button>
              </div>
            </TransitionChild>
            <div class="h-0 flex-1 overflow-y-auto pt-5 pb-4">
              <div class="flex flex-shrink-0 items-center px-4">
                <AppLink :to="{ name: 'dashboard' }">
                  <img
                    class="h-8 w-auto"
                    src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
                    alt="Canvas"
                  />
                </AppLink>
              </div>
              <nav class="mt-5 space-y-1 px-2">
                <AppLink
                  :to="{ name: 'dashboard' }"
                  class="group flex items-center px-2 py-2 text-base font-medium rounded-md"
                  active-class="bg-gray-100 text-gray-900 dark:text-white dark:bg-gray-900"
                  inactive-class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-white dark:text-gray-300 dark:bg-gray-800 dark:hover:text-white dark:hover:bg-gray-700"
                  @click="closeSidebar"
                >
                  <component
                    :is="HomeIcon"
                    :class="[
                      ['dashboard'].includes(route.name)
                        ? 'text-gray-500 dark:text-gray-300 dark:group-hover:text-gray-300'
                        : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-300 dark:group-hover:text-gray-300',
                      'mr-4 flex-shrink-0 h-6 w-6',
                    ]"
                    aria-hidden="true"
                  />
                  {{ trans.dashboard }}
                </AppLink>
                <AppLink
                  :to="{ name: 'users' }"
                  class="group flex items-center px-2 py-2 text-base font-medium rounded-md"
                  active-class="bg-gray-100 text-gray-900 dark:text-white dark:bg-gray-900"
                  inactive-class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-white dark:text-gray-300 dark:bg-gray-800 dark:hover:text-white dark:hover:bg-gray-700"
                  @click="closeSidebar"
                >
                  <component
                    :is="UserGroupIcon"
                    :class="[
                      ['users', 'create-user', 'show-user'].includes(route.name)
                        ? 'text-gray-500 dark:text-gray-300 dark:group-hover:text-gray-300'
                        : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-300 dark:group-hover:text-gray-300',
                      'mr-4 flex-shrink-0 h-6 w-6',
                    ]"
                    aria-hidden="true"
                  />
                  {{ trans.users }}
                </AppLink>
                <AppLink
                  :to="{ name: 'posts' }"
                  class="group flex items-center px-2 py-2 text-base font-medium rounded-md"
                  active-class="bg-gray-100 text-gray-900 dark:text-white dark:bg-gray-900"
                  inactive-class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-white dark:text-gray-300 dark:bg-gray-800 dark:hover:text-white dark:hover:bg-gray-700"
                  @click="closeSidebar"
                >
                  <component
                    :is="BookmarkSquareIcon"
                    :class="[
                      ['posts', 'create-post', 'show-post'].includes(route.name)
                        ? 'text-gray-500 dark:text-gray-300 dark:group-hover:text-gray-300'
                        : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-300 dark:group-hover:text-gray-300',
                      'mr-4 flex-shrink-0 h-6 w-6',
                    ]"
                    aria-hidden="true"
                  />
                  {{ trans.posts }}
                </AppLink>
                <AppLink
                  :to="{ name: 'tags' }"
                  class="group flex items-center px-2 py-2 text-base font-medium rounded-md"
                  active-class="bg-gray-100 text-gray-900 dark:text-white dark:bg-gray-900"
                  inactive-class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-white dark:text-gray-300 dark:bg-gray-800 dark:hover:text-white dark:hover:bg-gray-700"
                  @click="closeSidebar"
                >
                  <component
                    :is="TagIcon"
                    :class="[
                      ['tags', 'create-tag', 'show-tag'].includes(route.name)
                        ? 'text-gray-500 dark:text-gray-300 dark:group-hover:text-gray-300'
                        : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-300 dark:group-hover:text-gray-300',
                      'mr-4 flex-shrink-0 h-6 w-6',
                    ]"
                    aria-hidden="true"
                  />
                  {{ trans.tags }}
                </AppLink>
                <AppLink
                  :to="{ name: 'topics' }"
                  class="group flex items-center px-2 py-2 text-base font-medium rounded-md"
                  active-class="bg-gray-100 text-gray-900 dark:text-white dark:bg-gray-900"
                  inactive-class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-white dark:text-gray-300 dark:bg-gray-800 dark:hover:text-white dark:hover:bg-gray-700"
                  @click="closeSidebar"
                >
                  <component
                    :is="RectangleStackIcon"
                    :class="[
                      ['topics', 'create-topic', 'show-topic'].includes(
                        route.name
                      )
                        ? 'text-gray-500 dark:text-gray-300 dark:group-hover:text-gray-300'
                        : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-300 dark:group-hover:text-gray-300',
                      'mr-4 flex-shrink-0 h-6 w-6',
                    ]"
                    aria-hidden="true"
                  />
                  {{ trans.topics }}
                </AppLink>

                <hr
                  class="my-5 border-t border-gray-200 dark:border-gray-700"
                  aria-hidden="true"
                />

                <AppLink
                  :to="{ name: 'settings' }"
                  class="group flex items-center px-2 py-2 text-base font-medium rounded-md"
                  active-class="bg-gray-100 text-gray-900 dark:text-white dark:bg-gray-900"
                  inactive-class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-white dark:text-gray-300 dark:bg-gray-800 dark:hover:text-white dark:hover:bg-gray-700"
                  @click="closeSidebar"
                >
                  <component
                    :is="CogIcon"
                    :class="[
                      ['settings'].includes(route.name)
                        ? 'text-gray-500 dark:text-gray-300 dark:group-hover:text-gray-300'
                        : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-300 dark:group-hover:text-gray-300',
                      'mr-4 flex-shrink-0 h-6 w-6',
                    ]"
                    aria-hidden="true"
                  />
                  {{ trans.settings }}
                </AppLink>
                <a
                  class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-white dark:text-gray-300 dark:bg-gray-800 dark:hover:text-white dark:hover:bg-gray-700 cursor-pointer"
                  @click="logout"
                >
                  <component
                    :is="ArrowRightOnRectangleIcon"
                    class="mr-4 flex-shrink-0 h-6 w-6 text-gray-400 group-hover:text-gray-500 dark:text-gray-300 dark:group-hover:text-gray-300"
                    aria-hidden="true"
                  />
                  {{ trans.sign_out }}
                </a>
              </nav>
            </div>
            <div
              class="flex flex-shrink-0 border-t border-gray-200 p-4 dark:bg-gray-700 dark:border-transparent"
            >
              <AppLink
                :to="{
                  name: 'show-user',
                  params: { id: user.id },
                }"
                class="group block flex-shrink-0"
                @click="closeSidebar"
              >
                <div class="flex items-center">
                  <div>
                    <img
                      class="inline-block h-9 w-9 rounded-full"
                      :src="user.avatar || user.default_avatar"
                      :alt="user.name"
                    />
                  </div>
                  <div class="ml-3">
                    <p
                      class="text-sm font-medium text-gray-700 group-hover:text-gray-900 dark:text-white dark:group-hover:text-white"
                    >
                      {{ user.name }}
                    </p>
                    <p
                      class="text-xs font-medium text-gray-500 group-hover:text-gray-700 dark:text-gray-300 dark:group-hover:text-gray-200"
                    >
                      {{ trans.view_profile }}
                    </p>
                  </div>
                </div>
              </AppLink>
            </div>
          </DialogPanel>
        </TransitionChild>
        <div class="w-14 flex-shrink-0">
          <!-- Force sidebar to shrink to fit close icon -->
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { computed } from 'vue'
import {
  Dialog,
  DialogPanel,
  TransitionChild,
  TransitionRoot,
} from '@headlessui/vue'
import {
  ArrowRightOnRectangleIcon,
  TagIcon,
  CogIcon,
  BookmarkSquareIcon,
  HomeIcon,
  RectangleStackIcon,
  UserGroupIcon,
  XMarkIcon,
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

const emit = defineEmits(['closeSidebar', 'logout'])
const route = useRoute()
const store = useStore()
const user = computed(() => store.state.config.user)
const trans = computed(() => store.getters['config/trans'])

function closeSidebar() {
  emit('closeSidebar')
}

function logout() {
  emit('logout')
}
</script>
