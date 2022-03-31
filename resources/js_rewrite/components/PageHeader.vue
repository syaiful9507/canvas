<template>
    <Disclosure as="nav" class="bg-white shadow" v-slot="{ open }">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="relative flex items-center justify-between h-16">
                <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                    <!-- Mobile menu button -->
                    <DisclosureButton class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                        <MenuIcon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
                        <XIcon v-else class="block h-6 w-6" aria-hidden="true" />
                    </DisclosureButton>
                </div>
                <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">
                    <AppLink
                        :to="{ name: 'dashboard' }">
                        <div class="flex-shrink-0 flex items-center">
                            <img class="block lg:hidden h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg" alt="Workflow" />
                            <img class="hidden lg:block h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-logo-indigo-600-mark-gray-800-text.svg" alt="Workflow" />
                        </div>
                    </AppLink>

                    <!-- Desktop main navigation -->
                    <div class="hidden sm:block sm:ml-6">
                        <div class="flex space-x-4">
                            <AppLink
                                :to="{ name: 'posts' }"
                                class="rounded-md py-2 px-3 inline-flex items-center text-sm font-medium"
                                active-class="bg-gray-100 text-gray-900"
                                inactive-class="text-gray-900 hover:bg-gray-50 hover:text-gray-900">
                                {{ trans.posts }}
                            </AppLink>
                            <AppLink
                                :to="{ name: 'users' }"
                                class="rounded-md py-2 px-3 inline-flex items-center text-sm font-medium"
                                active-class="bg-gray-100 text-gray-900"
                                inactive-class="text-gray-900 hover:bg-gray-50 hover:text-gray-900">
                                {{ trans.users }}
                            </AppLink>
                            <AppLink
                                :to="{ name: 'tags' }"
                                class="rounded-md py-2 px-3 inline-flex items-center text-sm font-medium"
                                active-class="bg-gray-100 text-gray-900"
                                inactive-class="text-gray-900 hover:bg-gray-50 hover:text-gray-900">
                                {{ trans.tags }}
                            </AppLink>
                            <AppLink
                                :to="{ name: 'topics' }"
                                class="rounded-md py-2 px-3 inline-flex items-center text-sm font-medium"
                                active-class="bg-gray-100 text-gray-900"
                                inactive-class="text-gray-900 hover:bg-gray-50 hover:text-gray-900">
                                {{ trans.topics }}
                            </AppLink>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                    <button @click="openCommandPalette" type="button" class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <SearchIcon class="h-6 w-6" aria-hidden="true" />
                        <CommandPalette ref="palette"></CommandPalette>
                    </button>

                    <!-- Profile dropdown -->
                    <Menu as="div" class="ml-3 relative">
                        <div>
                            <MenuButton class="bg-gray-800 flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
                                <img class="h-8 w-8 rounded-full" :src="user.avatar || user.default_avatar" :alt="user.name" />
                            </MenuButton>
                        </div>
                        <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                            <MenuItems class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                                <AppLink
                                    :to="{ name: 'show-user', params: { id: user.id } }"
                                    class="block px-4 py-2 text-sm"
                                    inactive-class="text-gray-900 hover:bg-gray-50 hover:text-gray-900"
                                    active-class="bg-gray-100 text-gray-900">
                                    {{ trans.your_profile }}
                                </AppLink>
                                <AppLink
                                    :to="{ name: 'settings' }"
                                    class="block px-4 py-2 text-sm"
                                    inactive-class="text-gray-900 hover:bg-gray-50 hover:text-gray-900"
                                    active-class="bg-gray-100 text-gray-900">
                                    {{ trans.settings }}
                                </AppLink>
                                <a
                                    @click="logout"
                                    class="block px-4 py-2 text-sm text-gray-900 hover:bg-gray-50 hover:text-gray-900 cursor-pointer">
                                    {{ trans.sign_out }}
                                </a>
                            </MenuItems>
                        </transition>
                    </Menu>
                </div>
            </div>
        </div>

        <!-- Mobile main navigation -->
        <DisclosurePanel class="sm:hidden" v-slot="{ close }">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <AppLink
                    :to="{ name: 'posts' }"
                    @click="close"
                    class="block rounded-md py-2 px-3 text-base font-medium"
                    active-class="bg-gray-100 text-gray-900"
                    inactive-class="text-gray-900 hover:bg-gray-50 hover:text-gray-900">
                    {{ trans.posts }}
                </AppLink>
                <AppLink
                    :to="{ name: 'users' }"
                    @click="close"
                    class="block rounded-md py-2 px-3 text-base font-medium"
                    active-class="bg-gray-100 text-gray-900"
                    inactive-class="text-gray-900 hover:bg-gray-50 hover:text-gray-900">
                    {{ trans.users }}
                </AppLink>
                <AppLink
                    :to="{ name: 'tags' }"
                    @click="close"
                    class="block rounded-md py-2 px-3 text-base font-medium"
                    active-class="bg-gray-100 text-gray-900"
                    inactive-class="text-gray-900 hover:bg-gray-50 hover:text-gray-900">
                    {{ trans.tags }}
                </AppLink>
                <AppLink
                    :to="{ name: 'topics' }"
                    @click="close"
                    class="block rounded-md py-2 px-3 text-base font-medium"
                    active-class="bg-gray-100 text-gray-900"
                    inactive-class="text-gray-900 hover:bg-gray-50 hover:text-gray-900">
                    {{ trans.topics }}
                </AppLink>
            </div>
        </DisclosurePanel>
    </Disclosure>
</template>

<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItems } from '@headlessui/vue'
import { SearchIcon, MenuIcon, XIcon } from '@heroicons/vue/outline'
import AppLink from "@/components/AppLink";
import { useStore } from 'vuex';
import request from "@/request";
import CommandPalette from '@/components/CommandPalette'
import { computed, ref } from 'vue'

const store = useStore()
const user = computed(() => store.state.settings.user)
const trans = computed(() => store.getters["settings/trans"])
const palette = ref(null)

function openCommandPalette() {
    palette.value.show()
}

function logout() {
    request
        .post('logout')
        .then(() => {
            window.location.href = store.state.settings.path;
        });
}
</script>
