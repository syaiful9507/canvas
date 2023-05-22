<template>
    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 md:px-8">
            <div v-if="tag">
                <div>
                    <nav class="sm:hidden" :aria-label="trans.back">
                        <AppLink
                            :to="{ name: 'tags' }"
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
                                        :to="{ name: 'tags' }"
                                        class="ml-4 text-sm font-medium text-gray-500"
                                    >
                                        {{ trans.tags }}
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
                                        {{ tag.name }}
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
                            {{ tag.name }}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="py-4">
                <div
                    class="h-96 rounded-lg border-4 border-dashed border-gray-200"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue'
import { useStore } from 'vuex'
import request from '@/utils/request'
import AppLink from '@/components/AppLink'
import { ChevronRightIcon, ChevronLeftIcon } from '@heroicons/vue/24/solid'
import { useRoute } from 'vue-router'

const store = useStore()
const route = useRoute()
const trans = computed(() => store.getters['config/trans'])
const tag = ref(null)

onMounted(async () => {
    await fetchTag()
})

function fetchTag() {
    return request.get(`api/tags/${route.params.id}`).then(({ data }) => {
        tag.value = data
    })
}
</script>
