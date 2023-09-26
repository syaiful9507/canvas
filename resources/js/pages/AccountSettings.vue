<template>
  <div class="mx-auto max-w-7xl px-4 sm:px-6 md:px-8">
    <div class="pt-10 md:flex md:items-center md:justify-between">
      <div class="min-w-0 flex-1">
        <h2
          class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight dark:text-white"
        >
          {{ trans.settings }}
        </h2>
      </div>
    </div>
    <div class="py-4">
      <div class="mt-10 divide-y divide-gray-200 dark:divide-gray-700">
        <div class="space-y-1">
          <h3
            class="text-lg font-medium leading-6 text-gray-900 dark:text-white"
          >
            {{ trans.general }}
          </h3>
          <p class="max-w-2xl text-sm text-gray-500 dark:text-gray-300">
            {{ trans.configure_your_system_preferences }}
          </p>
        </div>
        <div class="mt-6">
          <dl class="divide-y divide-gray-200 dark:divide-gray-700">
            <div class="flex items-center justify-between py-4">
              <div class="text-sm font-medium text-gray-500 dark:text-gray-300">
                {{ trans.appearance }}
              </div>
              <Menu as="div" class="relative inline-block text-left">
                <div>
                  <MenuButton
                    class="inline-flex w-full justify-center rounded-md bg-gray-50 dark:bg-black dark:text-white dark:hover:bg-opacity-30 px-4 py-2 text-sm font-medium focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75"
                  >
                    <SunIcon
                      v-if="store.getters['config/isLightAppearance']"
                      class="mr-2 h-5 w-5 text-violet-400"
                      aria-hidden="true"
                    />
                    <MoonIcon
                      v-if="store.getters['config/isDarkAppearance']"
                      class="mr-2 h-5 w-5 text-violet-400"
                      aria-hidden="true"
                    />
                    <ComputerDesktopIcon
                      v-if="store.getters['config/isSystemAppearance']"
                      class="mr-2 h-5 w-5 text-violet-400"
                      aria-hidden="true"
                    />
                    {{ store.getters['config/getAppearanceName'] }}
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
                    class="absolute right-0 z-10 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white dark:bg-gray-900 dark:bg-opacity-80 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                  >
                    <div class="px-1 py-1">
                      <MenuItem v-slot="{ active }">
                        <button
                          :class="[
                            active
                              ? 'bg-violet-500 dark:bg-white dark:bg-opacity-20 text-white dark:text-white'
                              : 'text-gray-900 dark:text-white',
                            'group flex w-full items-center rounded-md px-2 py-2 text-sm'
                          ]"
                          @click="
                            store.dispatch('config/updateAppearance', false)
                          "
                        >
                          <SunIcon
                            class="mr-2 h-5 w-5 text-violet-400"
                            aria-hidden="true"
                          />
                          {{ trans.light }}
                          <span
                            v-if="store.getters['config/isLightAppearance']"
                            class="ml-auto flex items-center pl-3 text-indigo-500 dark:text-white"
                          >
                            <CheckIcon class="h-5 w-5" aria-hidden="true" />
                          </span>
                        </button>
                      </MenuItem>
                      <MenuItem v-slot="{ active }">
                        <button
                          :class="[
                            active
                              ? 'bg-violet-500 dark:bg-white dark:bg-opacity-20 text-white dark:text-white'
                              : 'text-gray-900 dark:text-white',
                            'group flex w-full items-center rounded-md px-2 py-2 text-sm'
                          ]"
                          @click="
                            store.dispatch('config/updateAppearance', true)
                          "
                        >
                          <MoonIcon
                            class="mr-2 h-5 w-5 text-violet-400"
                            aria-hidden="true"
                          />
                          {{ trans.dark }}
                          <span
                            v-if="store.getters['config/isDarkAppearance']"
                            class="ml-auto flex items-center pl-3 text-indigo-500 dark:text-white"
                          >
                            <CheckIcon class="h-5 w-5" aria-hidden="true" />
                          </span>
                        </button>
                      </MenuItem>
                      <MenuItem v-slot="{ active }">
                        <button
                          :class="[
                            active
                              ? 'bg-violet-500 dark:bg-white dark:bg-opacity-20 text-white dark:text-white'
                              : 'text-gray-900 dark:text-white',
                            'group flex w-full items-center rounded-md px-2 py-2 text-sm'
                          ]"
                          @click="
                            store.dispatch('config/updateAppearance', null)
                          "
                        >
                          <ComputerDesktopIcon
                            class="mr-2 h-5 w-5 text-violet-400"
                            aria-hidden="true"
                          />
                          {{ trans.system }}
                          <span
                            v-if="store.getters['config/isSystemAppearance']"
                            class="ml-auto flex items-center pl-3 text-indigo-500 dark:text-white"
                          >
                            <CheckIcon class="h-5 w-5" aria-hidden="true" />
                          </span>
                        </button>
                      </MenuItem>
                    </div>
                  </MenuItems>
                </transition>
              </Menu>
            </div>

            <div class="flex items-center justify-between py-4">
              <div class="text-sm font-medium text-gray-500 dark:text-gray-300">
                {{ trans.language }}
              </div>
              <Menu as="div" class="relative inline-block text-left">
                <div>
                  <MenuButton
                    class="inline-flex w-full justify-center rounded-md bg-black bg-opacity-20 px-4 py-2 text-sm font-medium text-white hover:bg-opacity-30 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75"
                  >
                    {{
                      store.getters['config/getLocaleDisplayName'](
                        store.getters['config/locale']
                      )
                    }}
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
                    class="absolute right-0 z-10 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md dark:bg-gray-900 dark:bg-opacity-80 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none backdrop-blur backdrop-filter"
                  >
                    <div class="px-1 py-1">
                      <MenuItem
                        v-for="code in config.languageCodes"
                        :key="code"
                        v-slot="{ active }"
                      >
                        <button
                          :class="[
                            active
                              ? 'bg-violet-500 dark:bg-white dark:bg-opacity-20 text-white dark:text-white'
                              : 'text-gray-900 dark:text-white',
                            'group flex w-full items-center rounded-md px-2 py-2 text-sm'
                          ]"
                          @click="store.dispatch('config/updateLocale', code)"
                        >
                          {{
                            store.getters['config/getLocaleDisplayName'](code)
                          }}
                          <span
                            v-if="code === store.getters['config/locale']"
                            class="ml-auto flex items-center pl-3 text-indigo-500 dark:text-white"
                          >
                            <CheckIcon class="h-5 w-5" aria-hidden="true" />
                          </span>
                        </button>
                      </MenuItem>
                    </div>
                  </MenuItems>
                </transition>
              </Menu>
            </div>

            <div class="flex items-center justify-between py-4">
              <div class="text-sm font-medium text-gray-500 dark:text-gray-300">
                {{ trans.version }}
              </div>
              <div class="relative inline-block text-left">
                <p class="text-sm text-gray-600 dark:text-gray-300 py-2">
                  {{ config.version }}
                </p>
              </div>
            </div>
          </dl>
        </div>
      </div>
    </div>

    <footer class="mx-auto mt-32 w-full max-w-container px-4 sm:px-6 lg:px-8">
      <div class="py-10">
        <img
          class="h-8 mx-auto"
          src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
          alt="Canvas"
        />
        <p
          class="mt-5 text-center text-sm leading-6 text-gray-500 dark:text-gray-300"
        >
          {{ trans.canvas_is_open_sourced_software }}
        </p>
        <div
          class="mt-16 flex items-center justify-center space-x-4 text-sm font-semibold leading-6 text-gray-600 dark:text-gray-300"
        >
          <AppLink to="https://github.com/austintoddj/canvas#introduction">
            {{ trans.documentation }}
          </AppLink>
          <div class="h-4 w-px bg-gray-500/20 dark:bg-gray-300/20"></div>
          <AppLink to="https://github.com/austintoddj/canvas/releases">
            {{ trans.changelog }}
          </AppLink>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import AppLink from '@/components/AppLink'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import {
  CheckIcon,
  ChevronDownIcon,
  ComputerDesktopIcon,
  MoonIcon,
  SunIcon
} from '@heroicons/vue/24/outline'
import { computed } from 'vue'
import { useStore } from 'vuex'

const store = useStore()
const config = computed(() => store.state.config)
const trans = computed(() => store.getters['config/trans'])
</script>
