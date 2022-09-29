<template>
  <TransitionRoot :show="isOpen" as="template" appear @after-leave="query = ''">
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
            class="mx-auto max-w-xl transform divide-y divide-gray-100 overflow-hidden rounded-xl bg-white shadow-2xl ring-1 ring-black ring-opacity-5 transition-all"
          >
            <Combobox
              as="div"
              class="mx-auto max-w-xl transform divide-y divide-gray-100 overflow-hidden rounded-xl bg-white shadow-2xl ring-1 ring-black ring-opacity-5 transition-all"
              @update:model-value="onSelect"
            >
              <div class="relative">
                <MagnifyingGlassIcon
                  class="pointer-events-none absolute top-3.5 left-4 h-5 w-5 text-gray-400"
                  aria-hidden="true"
                />
                <ComboboxInput
                  class="h-12 w-full border-0 bg-transparent pl-11 pr-4 text-gray-800 placeholder-gray-400 focus:ring-0 sm:text-sm"
                  :placeholder="trans.search_canvas"
                  @change="query = $event.target.value"
                />
              </div>

              <ComboboxOptions
                v-if="filteredItems.length > 0"
                static
                class="max-h-80 scroll-pt-11 scroll-pb-2 space-y-2 overflow-y-auto pb-2"
              >
                <li
                  v-for="[category, items] in Object.entries(groups)"
                  :key="category"
                >
                  <h2
                    class="bg-gray-100 py-2.5 px-4 text-xs font-semibold text-gray-900"
                  >
                    {{ category }}
                  </h2>
                  <ul class="mt-2 text-sm text-gray-800">
                    <ComboboxOption
                      v-for="item in items"
                      :key="item.id"
                      v-slot="{ active }"
                      :value="item"
                      as="template"
                    >
                      <AppLink
                        :to="{
                          name: item.route,
                          params: { id: item.id },
                        }"
                      >
                        <li
                          :class="[
                            'cursor-pointer select-none px-4 py-2',
                            active && 'bg-indigo-600 text-white',
                          ]"
                        >
                          {{ item.name }}
                        </li>
                      </AppLink>
                    </ComboboxOption>
                  </ul>
                </li>
              </ComboboxOptions>
              <div
                v-if="query !== '' && filteredItems.length === 0"
                class="py-14 px-6 text-center text-sm sm:px-14"
              >
                <ExclamationTriangleIcon
                  class="mx-auto h-6 w-6 text-gray-400"
                  aria-hidden="true"
                />
                <p class="mt-4 font-semibold text-gray-900">No results found</p>
                <p class="mt-2 text-gray-500">
                  We couldn’t find anything with that term. Please try again.
                </p>
              </div>

              <div
                class="flex flex-wrap items-right bg-gray-50 py-2.5 px-4 text-xs text-gray-700"
              >
                Open with Ctrl/⌘ + K
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
import { ExclamationTriangleIcon } from '@heroicons/vue/24/outline'
import AppLink from '@/components/AppLink'
import {
  Combobox,
  ComboboxInput,
  ComboboxOptions,
  ComboboxOption,
  Dialog,
  DialogPanel,
  TransitionChild,
  TransitionRoot,
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
    : search.value.filter((item) => {
        return item.name.toLowerCase().includes(query.value.toLowerCase())
      })
)

const groups = computed(() =>
  filteredItems.value.reduce((groups, item) => {
    return {
      ...groups,
      [item.category]: [...(groups[item.category] || []), item],
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
    // TODO: Pressing Escape after a query is typed throws "undefined" in the input instead of clearing it
    // Related issue: https://github.com/tailwindlabs/headlessui/issues/1861

    if (event.key === 'k' && (event.metaKey || event.ctrlKey)) {
      show()
    }
  }

  window.addEventListener('keydown', onKeydown)

  return () => {
    window.removeEventListener('keydown', onKeydown)
  }
})

function onSelect(item) {
  hide()
  router.push({
    name: item.route,
    params: {
      id: item.id,
    },
  })
}

defineExpose({
  show,
  hide,
})

onMounted(() => {
  store.dispatch('search/buildIndex')
})
</script>
