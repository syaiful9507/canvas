<template>
    <TransitionRoot
        :show="isOpen"
        as="template"
        appear
        @after-leave="query = ''"
    >
        <Dialog
            as="div"
            class="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 md:p-20"
            @close="hide"
        >
            <TransitionChild
                as="template"
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-25 transition-opacity"
                />
            </TransitionChild>

            <div class="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 md:p-20">
                <TransitionChild
                    as="template"
                    enter="ease-out duration-300"
                    enter-from="opacity-0 scale-95"
                    enter-to="opacity-100 scale-100"
                    leave="ease-in duration-200"
                    leave-from="opacity-100 scale-100"
                    leave-to="opacity-0 scale-95"
                >
                    <DialogPanel
                        class="mx-auto max-w-2xl transform divide-y divide-gray-100 dark:divide-gray-500 dark:divide-opacity-20 overflow-hidden rounded-xl bg-white dark:bg-gray-900 shadow-2xl ring-1 ring-black ring-opacity-5 transition-all"
                    >
                        <Combobox @update:model-value="onSelect">
                            <div class="relative">
                                <MagnifyingGlassIcon
                                    class="pointer-events-none absolute top-3.5 left-4 h-5 w-5 text-gray-400 dark:text-gray-500"
                                    aria-hidden="true"
                                />
                                <ComboboxInput
                                    class="h-12 w-full border-0 pl-11 pr-4 bg-transparent text-gray-800 placeholder-gray-400 dark:bg-gray-900 dark:text-white focus:ring-0"
                                    :placeholder="trans.search_or_jump_to"
                                    @change="query = $event.target.value"
                                />
                            </div>

                            <ComboboxOptions
                                v-if="filteredItems.length > 0"
                                static
                                hold
                                class="max-h-80 scroll-py-2 divide-y divide-gray-100 dark:divide-gray-500 dark:divide-opacity-20 overflow-y-auto"
                            >
                                <li
                                    v-for="[category, items] in Object.entries(
                                        groups
                                    )"
                                    :key="category"
                                    class="p-2"
                                >
                                    <ul class="text-sm dark:text-gray-400">
                                        <ComboboxOption
                                            v-for="item in items"
                                            :key="item.id"
                                            v-slot="{ active }"
                                            :value="item"
                                            as="template"
                                        >
                                            <li>
                                                <AppLink
                                                    :to="{
                                                        name: item.route,
                                                        params: { id: item.id }
                                                    }"
                                                    :class="[
                                                        'flex cursor-pointer select-none items-center rounded-md px-3 py-2',
                                                        active &&
                                                            'bg-gray-100 text-gray-900 dark:text-white dark:bg-gray-800'
                                                    ]"
                                                >
                                                    <UserIcon
                                                        v-if="
                                                            item.route ===
                                                            'show-user'
                                                        "
                                                        :class="[
                                                            'h-6 w-6 flex-none',
                                                            active
                                                                ? 'text-gray-800 dark:text-white'
                                                                : 'text-gray-400'
                                                        ]"
                                                        aria-hidden="true"
                                                    />
                                                    <BookmarkSquareIcon
                                                        v-if="
                                                            item.route ===
                                                            'show-post'
                                                        "
                                                        :class="[
                                                            'h-6 w-6 flex-none',
                                                            active
                                                                ? 'text-gray-800 dark:text-white'
                                                                : 'text-gray-400'
                                                        ]"
                                                        aria-hidden="true"
                                                    />
                                                    <TagIcon
                                                        v-if="
                                                            item.route ===
                                                            'show-tag'
                                                        "
                                                        :class="[
                                                            'h-6 w-6 flex-none',
                                                            active
                                                                ? 'text-gray-800 dark:text-white'
                                                                : 'text-gray-400'
                                                        ]"
                                                        aria-hidden="true"
                                                    />
                                                    <RectangleStackIcon
                                                        v-if="
                                                            item.route ===
                                                            'show-topic'
                                                        "
                                                        :class="[
                                                            'h-6 w-6 flex-none',
                                                            active
                                                                ? 'text-gray-800 dark:text-white'
                                                                : 'text-gray-400'
                                                        ]"
                                                        aria-hidden="true"
                                                    />
                                                    <span
                                                        class="ml-3 flex-auto truncate"
                                                        >{{ item.name }}</span
                                                    >
                                                    <span
                                                        v-if="active"
                                                        class="ml-3 flex-none text-gray-400 dark:text-gray-400"
                                                        >{{
                                                            trans.jump_to
                                                        }}</span
                                                    >
                                                </AppLink>
                                            </li>
                                        </ComboboxOption>
                                    </ul>
                                </li>
                            </ComboboxOptions>
                            <div
                                v-if="
                                    query !== '' && filteredItems.length === 0
                                "
                                class="py-14 px-6 text-center text-sm sm:px-14"
                            >
                                <ExclamationTriangleIcon
                                    class="mx-auto h-6 w-6 text-gray-400 dark:text-gray-500"
                                    aria-hidden="true"
                                />
                                <p
                                    class="mt-4 font-semibold text-gray-900 dark:text-white"
                                >
                                    {{ trans.no_results_found }}
                                </p>
                                <p
                                    class="mt-2 text-gray-500 dark:text-gray-200"
                                >
                                    {{ trans.we_could_not_find_anything }}
                                </p>
                            </div>
                        </Combobox>
                    </DialogPanel>
                </TransitionChild>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { computed, ref, onMounted, watchEffect } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import { MagnifyingGlassIcon } from '@heroicons/vue/20/solid'
import {
    ExclamationTriangleIcon,
    TagIcon,
    BookmarkSquareIcon,
    RectangleStackIcon,
    UserIcon
} from '@heroicons/vue/24/outline'
import AppLink from '@/components/AppLink'
import {
    Combobox,
    ComboboxInput,
    ComboboxOptions,
    ComboboxOption,
    Dialog,
    DialogPanel,
    TransitionChild,
    TransitionRoot
} from '@headlessui/vue'

const isOpen = ref(false)
const query = ref('')
const router = useRouter()
const store = useStore()
const trans = computed(() => store.getters['config/trans'])
const search = computed(() => store.getters['search/index'])

const filteredItems = computed(() =>
    query.value === ''
        ? []
        : search.value.filter(item => {
              return item.name.toLowerCase().includes(query.value.toLowerCase())
          })
)

const groups = computed(() =>
    filteredItems.value.reduce((groups, item) => {
        return {
            ...groups,
            [item.category]: [...(groups[item.category] || []), item]
        }
    }, {})
)

const show = function () {
    isOpen.value = true
}

const hide = function () {
    isOpen.value = false
}

watchEffect(() => {
    function onKeydown(event) {
        if (event.key === 'k' && (event.metaKey || event.ctrlKey)) {
            show()
        }
    }

    window.addEventListener('keydown', onKeydown)

    return () => {
        window.removeEventListener('keydown', onKeydown)
    }
})

defineExpose({
    show,
    hide
})

onMounted(() => {
    store.dispatch('search/buildIndex')
})

function onSelect(item) {
    hide()
    router.push({
        name: item.route,
        params: {
            id: item.id
        }
    })
}
</script>
