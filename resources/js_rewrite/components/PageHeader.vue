<template>
    <Disclosure as="nav" class="bg-white shadow" v-slot="{ open }">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="relative flex items-center justify-between h-16">
                <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                    <!-- Mobile menu button -->
                    <DisclosureButton class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                        <span class="sr-only">Open main menu</span>
                        <MenuIcon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
                        <XIcon v-else class="block h-6 w-6" aria-hidden="true" />
                    </DisclosureButton>
                </div>
                <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">
                    <div class="flex-shrink-0 flex items-center">
                        <img class="block lg:hidden h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg" alt="Workflow" />
                        <img class="hidden lg:block h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-logo-indigo-600-mark-gray-800-text.svg" alt="Workflow" />
                    </div>

                    <!-- Desktop main navigation -->
                    <div class="hidden sm:block sm:ml-6">
                        <div class="flex space-x-4">
                            <app-link
                                :to="{ name: 'posts' }"
                                class="rounded-md py-2 px-3 inline-flex items-center text-sm font-medium"
                                active-class="bg-gray-100 text-gray-900"
                                inactive-class="text-gray-900 hover:bg-gray-50 hover:text-gray-900">
                                Posts
                            </app-link>
                            <app-link
                                :to="{ name: 'users' }"
                                class="rounded-md py-2 px-3 inline-flex items-center text-sm font-medium"
                                active-class="bg-gray-100 text-gray-900"
                                inactive-class="text-gray-900 hover:bg-gray-50 hover:text-gray-900">
                                Users
                            </app-link>
                            <app-link
                                :to="{ name: 'tags' }"
                                class="rounded-md py-2 px-3 inline-flex items-center text-sm font-medium"
                                active-class="bg-gray-100 text-gray-900"
                                inactive-class="text-gray-900 hover:bg-gray-50 hover:text-gray-900">
                                Tags
                            </app-link>
                            <app-link
                                :to="{ name: 'topics' }"
                                class="rounded-md py-2 px-3 inline-flex items-center text-sm font-medium"
                                active-class="bg-gray-100 text-gray-900"
                                inactive-class="text-gray-900 hover:bg-gray-50 hover:text-gray-900">
                                Topics
                            </app-link>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                    <button type="button" class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span class="sr-only">View notifications</span>
                        <SearchIcon class="h-6 w-6" aria-hidden="true" />
                    </button>

                    <!-- Profile dropdown -->
                    <Menu as="div" class="ml-3 relative">
                        <div>
                            <MenuButton class="bg-gray-800 flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
                                <span class="sr-only">Open user menu</span>
                                <img class="h-8 w-8 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
                            </MenuButton>
                        </div>
                        <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                            <MenuItems class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                                <app-link
                                    :to="{ name: 'show-user', params: { id: 1 } }"
                                    class="block px-4 py-2 text-sm"
                                    inactive-class="text-gray-900 hover:bg-gray-50 hover:text-gray-900"
                                    active-class="bg-gray-100 text-gray-900">
                                    Your Profile
                                </app-link>
                                <app-link
                                    :to="{ name: 'settings' }"
                                    class="block px-4 py-2 text-sm"
                                    inactive-class="text-gray-900 hover:bg-gray-50 hover:text-gray-900"
                                    active-class="bg-gray-100 text-gray-900">
                                    Settings
                                </app-link>
                                <a
                                    @click="logout"
                                    class="block px-4 py-2 text-sm text-gray-900 hover:bg-gray-50 hover:text-gray-900 cursor-pointer">
                                    Sign out
                                </a>
                            </MenuItems>
                        </transition>
                    </Menu>
                </div>
            </div>
        </div>

        <!-- Mobile main navigation -->
        <DisclosurePanel class="sm:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <app-link
                    :to="{ name: 'posts' }"
                    class="block rounded-md py-2 px-3 text-base font-medium"
                    active-class="bg-gray-100 text-gray-900"
                    inactive-class="text-gray-900 hover:bg-gray-50 hover:text-gray-900">
                    Posts
                </app-link>
                <app-link
                    :to="{ name: 'users' }"
                    class="block rounded-md py-2 px-3 text-base font-medium"
                    active-class="bg-gray-100 text-gray-900"
                    inactive-class="text-gray-900 hover:bg-gray-50 hover:text-gray-900">
                    Users
                </app-link>
                <app-link
                    :to="{ name: 'tags' }"
                    class="block rounded-md py-2 px-3 text-base font-medium"
                    active-class="bg-gray-100 text-gray-900"
                    inactive-class="text-gray-900 hover:bg-gray-50 hover:text-gray-900">
                    Tags
                </app-link>
                <app-link
                    :to="{ name: 'topics' }"
                    class="block rounded-md py-2 px-3 text-base font-medium"
                    active-class="bg-gray-100 text-gray-900"
                    inactive-class="text-gray-900 hover:bg-gray-50 hover:text-gray-900">
                    Topics
                </app-link>
            </div>
        </DisclosurePanel>
    </Disclosure>

    <search-modal></search-modal>
</template>

<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItems } from '@headlessui/vue'
import { SearchIcon, MenuIcon, XIcon } from '@heroicons/vue/outline'
import AppLink from "@/components/AppLink";
import { store} from "@/store";
import request from "@/request";
import SearchModal from '@/components/modals/SearchModal'

function logout() {
    request
        .post('logout')
        .then(() => {
            window.location.href = store.state.settings.path;
        });
}
</script>
