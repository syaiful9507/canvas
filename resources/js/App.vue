<template>
  <!-- Mobile Sidebar -->
  <TransitionRoot as="template" :show="sidebarOpen">
    <Dialog
      as="div"
      class="relative z-40 md:hidden"
      @close="sidebarOpen = false"
    >
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
            class="relative flex w-full max-w-xs flex-1 flex-col bg-white"
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
                <img
                  class="h-8 w-auto"
                  src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
                  alt="Canvas"
                />
              </div>
              <nav class="mt-5 space-y-1 px-2">
                <AppLink
                  :to="{ name: 'dashboard' }"
                  class="group flex items-center px-2 py-2 text-base font-medium rounded-md"
                  active-class="bg-gray-100 text-gray-900"
                  inactive-class="text-gray-600 hover:bg-gray-50 hover:text-gray-900"
                  @click="closeSidebar"
                >
                  <component
                    :is="HomeIcon"
                    :class="[
                      ['dashboard'].includes($route.name)
                        ? 'text-gray-500'
                        : 'text-gray-400 group-hover:text-gray-500',
                      'mr-4 flex-shrink-0 h-6 w-6',
                    ]"
                    aria-hidden="true"
                  />
                  {{ trans.dashboard }}
                </AppLink>
                <AppLink
                  :to="{ name: 'users' }"
                  class="group flex items-center px-2 py-2 text-base font-medium rounded-md"
                  active-class="bg-gray-100 text-gray-900"
                  inactive-class="text-gray-600 hover:bg-gray-50 hover:text-gray-900"
                  @click="closeSidebar"
                >
                  <component
                    :is="UserGroupIcon"
                    :class="[
                      ['users', 'create-user', 'show-user'].includes(
                        $route.name
                      )
                        ? 'text-gray-500'
                        : 'text-gray-400 group-hover:text-gray-500',
                      'mr-4 flex-shrink-0 h-6 w-6',
                    ]"
                    aria-hidden="true"
                  />
                  {{ trans.users }}
                </AppLink>
                <AppLink
                  :to="{ name: 'posts' }"
                  class="group flex items-center px-2 py-2 text-base font-medium rounded-md"
                  active-class="bg-gray-100 text-gray-900"
                  inactive-class="text-gray-600 hover:bg-gray-50 hover:text-gray-900"
                  @click="closeSidebar"
                >
                  <component
                    :is="BookmarkSquareIcon"
                    :class="[
                      ['posts', 'create-post', 'show-post'].includes(
                        $route.name
                      )
                        ? 'text-gray-500'
                        : 'text-gray-400 group-hover:text-gray-500',
                      'mr-4 flex-shrink-0 h-6 w-6',
                    ]"
                    aria-hidden="true"
                  />
                  {{ trans.posts }}
                </AppLink>
                <AppLink
                  :to="{ name: 'tags' }"
                  class="group flex items-center px-2 py-2 text-base font-medium rounded-md"
                  active-class="bg-gray-100 text-gray-900"
                  inactive-class="text-gray-600 hover:bg-gray-50 hover:text-gray-900"
                  @click="closeSidebar"
                >
                  <component
                    :is="TagIcon"
                    :class="[
                      ['tags', 'create-tag', 'show-tag'].includes($route.name)
                        ? 'text-gray-500'
                        : 'text-gray-400 group-hover:text-gray-500',
                      'mr-4 flex-shrink-0 h-6 w-6',
                    ]"
                    aria-hidden="true"
                  />
                  {{ trans.tags }}
                </AppLink>
                <AppLink
                  :to="{ name: 'topics' }"
                  class="group flex items-center px-2 py-2 text-base font-medium rounded-md"
                  active-class="bg-gray-100 text-gray-900"
                  inactive-class="text-gray-600 hover:bg-gray-50 hover:text-gray-900"
                  @click="closeSidebar"
                >
                  <component
                    :is="RectangleStackIcon"
                    :class="[
                      ['topics', 'create-topic', 'show-topic'].includes(
                        $route.name
                      )
                        ? 'text-gray-500'
                        : 'text-gray-400 group-hover:text-gray-500',
                      'mr-4 flex-shrink-0 h-6 w-6',
                    ]"
                    aria-hidden="true"
                  />
                  {{ trans.topics }}
                </AppLink>

                <hr class="my-5 border-t border-gray-200" aria-hidden="true" />

                <AppLink
                  :to="{ name: 'settings' }"
                  class="group flex items-center px-2 py-2 text-base font-medium rounded-md"
                  active-class="bg-gray-100 text-gray-900"
                  inactive-class="text-gray-600 hover:bg-gray-50 hover:text-gray-900"
                  @click="closeSidebar"
                >
                  <component
                    :is="CogIcon"
                    :class="[
                      ['settings'].includes($route.name)
                        ? 'text-gray-500'
                        : 'text-gray-400 group-hover:text-gray-500',
                      'mr-4 flex-shrink-0 h-6 w-6',
                    ]"
                    aria-hidden="true"
                  />
                  {{ trans.settings }}
                </AppLink>
                <a
                  class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900 cursor-pointer"
                  @click="logout"
                >
                  <component
                    :is="ArrowRightOnRectangleIcon"
                    class="mr-4 flex-shrink-0 h-6 w-6 text-gray-400 group-hover:text-gray-500"
                    aria-hidden="true"
                  />
                  {{ trans.sign_out }}
                </a>
              </nav>
            </div>
            <div class="flex flex-shrink-0 border-t border-gray-200 p-4">
              <AppLink
                :to="{
                  name: 'show-user',
                  params: { id: user.id },
                }"
                class="group block flex-shrink-0"
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
          </DialogPanel>
        </TransitionChild>
        <div class="w-14 flex-shrink-0">
          <!-- Force sidebar to shrink to fit close icon -->
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
  <!-- End Mobile Sidebar -->

  <!-- Desktop Sidebar -->
  <div class="hidden md:fixed md:inset-y-0 md:flex md:w-64 md:flex-col">
    <div
      class="flex min-h-0 flex-1 flex-col border-r border-gray-200 bg-gray-100"
    >
      <div class="flex flex-1 flex-col overflow-y-auto pt-5 pb-4">
        <div class="flex flex-shrink-0 items-center px-4">
          <img
            class="h-8 w-auto"
            src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
            alt="Your Company"
          />
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
                  ['dashboard'].includes($route.name)
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
                  ['users', 'create-user', 'show-user'].includes($route.name)
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
                  ['posts', 'create-post', 'show-post'].includes($route.name)
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
                  ['tags', 'create-tag', 'show-tag'].includes($route.name)
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
                  ['topics', 'create-topic', 'show-topic'].includes($route.name)
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
                  ['settings'].includes($route.name)
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
  <!-- End Desktop Sidebar -->

  <div class="flex flex-1 flex-col md:pl-64">
    <div class="sticky top-0 z-10 bg-white pl-1 pt-1 sm:pl-3 sm:pt-3 md:hidden">
      <button
        type="button"
        class="-ml-0.5 -mt-0.5 inline-flex h-12 w-12 items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
        @click="openSidebar"
      >
        <span class="sr-only">{{ trans.open_sidebar }}</span>
        <Bars3Icon class="h-6 w-6" aria-hidden="true" />
      </button>
    </div>
    <main class="flex-1">
      <!-- Outdated Assets Alert -->
      <div v-if="!config.assetsUpToDate" class="bg-blue-50 p-4">
        <div class="flex">
          <div class="flex-shrink-0">
            <InformationCircleIcon
              class="h-5 w-5 text-blue-400"
              aria-hidden="true"
            />
          </div>
          <div class="ml-3 flex-1 md:flex md:justify-between">
            <p class="text-sm text-blue-700">
              {{ trans.new_assets_are_available_to_publish }}
            </p>
            <p class="mt-3 text-sm md:mt-0 md:ml-6">
              <a
                href="https://github.com/austintoddj/canvas#updates"
                target="_blank"
                class="whitespace-nowrap font-medium text-blue-700 hover:text-blue-600"
              >
                {{ trans.details }}
                <span aria-hidden="true"> &rarr;</span>
              </a>
            </p>
          </div>
        </div>
      </div>
      <!-- End Outdated Assets Alert -->

      <RouterView :key="$route.query"></RouterView>
    </main>
  </div>

  <CommandPalette ref="palette"></CommandPalette>
</template>

<script setup>
import { computed, ref } from 'vue'
import {
  Dialog,
  DialogPanel,
  TransitionChild,
  TransitionRoot,
} from '@headlessui/vue'
import {
  ArrowRightOnRectangleIcon,
  Bars3Icon,
  TagIcon,
  CogIcon,
  BookmarkSquareIcon,
  HomeIcon,
  RectangleStackIcon,
  InformationCircleIcon,
  UserGroupIcon,
  XMarkIcon,
} from '@heroicons/vue/24/outline'
import { useStore } from 'vuex'
import AppLink from '@/components/AppLink'
import CommandPalette from '@/components/CommandPalette'
import request from '@/utils/request'

const store = useStore()
const user = computed(() => store.state.config.user)
const config = computed(() => store.state.config)
const trans = computed(() => store.getters['config/trans'])
const palette = ref(null)
const sidebarOpen = ref(false)

function openSidebar() {
  sidebarOpen.value = true
}

function closeSidebar() {
  sidebarOpen.value = false
}

function logout() {
  return request.post('logout').then(() => {
    window.location.href = config.value.path === '' ? '/' : config.value.path
  })
}
</script>
