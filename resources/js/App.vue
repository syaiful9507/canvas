<template>
    <MobileSidebar
        :open="sidebarOpen"
        @close-sidebar="closeSidebar"
        @logout="logout"
    />
    <DesktopSidebar @logout="logout" />
    <div class="flex flex-1 flex-col md:pl-64">
        <div
            class="sticky top-0 z-10 bg-white dark:bg-gray-800 pl-1 pt-1 sm:pl-3 sm:pt-3 md:hidden"
        >
            <button
                type="button"
                class="-ml-0.5 -mt-0.5 inline-flex h-12 w-12 items-center justify-center rounded-md text-gray-500 hover:text-gray-900 dark:text-gray-300 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                @click="openSidebar"
            >
                <span class="sr-only">{{ trans.open_sidebar }}</span>
                <Bars3Icon class="h-6 w-6" aria-hidden="true" />
            </button>
        </div>
        <main class="flex-1">
            <RouterView :key="route.query"></RouterView>
        </main>
    </div>
    <CommandPalette ref="palette"></CommandPalette>
    <SimpleNotification
        v-if="!config.assetsUpToDate"
        type="update"
        :title="trans.heads_up_loud"
        :description="trans.new_assets_are_available_to_publish"
        url="https://github.com/austintoddj/canvas#updates"
    ></SimpleNotification>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue'
import { Bars3Icon } from '@heroicons/vue/24/outline'
import { useStore } from 'vuex'
import CommandPalette from '@/components/CommandPalette'
import MobileSidebar from '@/components/MobileSidebar'
import DesktopSidebar from '@/components/DesktopSidebar'
import request from '@/utils/request'
import {
    setDarkTheme,
    setLightTheme,
    prefersDarkColorScheme
} from '@/utils/theme'
import { useRoute } from 'vue-router'
import SimpleNotification from '@/components/SimpleNotification'

const route = useRoute()
const store = useStore()
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
        window.location.href =
            config.value.path === '' ? '/' : config.value.path
    })
}

onMounted(() => {
    // Set the theme on initial page load
    if (
        store.getters['config/isDarkAppearance'] ||
        (store.getters['config/isSystemAppearance'] && prefersDarkColorScheme())
    ) {
        setDarkTheme()
    } else {
        setLightTheme()
    }

    // Watch for OS System changes
    window
        .matchMedia('(prefers-color-scheme: dark)')
        .addEventListener('change', function () {
            if (
                store.getters['config/isDarkAppearance'] ||
                (store.getters['config/isSystemAppearance'] &&
                    prefersDarkColorScheme())
            ) {
                setDarkTheme()
            } else {
                setLightTheme()
            }
        })
})
</script>
