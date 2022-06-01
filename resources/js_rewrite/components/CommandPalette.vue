<template>
  <TransitionRoot :show="isOpen" as="template" @after-leave="query = ''" appear>
    <Dialog as="div" class="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 md:p-20" @close="hide">
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
                <SearchIcon
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
                v-if="filteredSearch.length > 0"
                static
                class="max-h-72 scroll-py-2 overflow-y-auto py-2 text-sm text-gray-800"
              >
                <ComboboxOption
                  v-for="item in filteredSearch"
                  :key="item.id"
                  v-slot="{ active }"
                  :value="item"
                  as="template"
                >
                  <li
                    :class="[
                      'cursor-pointer select-none px-4 py-2',
                      active && 'bg-indigo-600 text-white',
                    ]"
                  >
                    {{ item.name
                    }}<span :class="['text-gray-400', active && 'opacity-75']">
                      ― {{ item.type }}</span
                    >
                  </li>
                </ComboboxOption>
              </ComboboxOptions>
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
import { SearchIcon } from '@heroicons/vue/solid'
import {
  Combobox,
  ComboboxInput,
  ComboboxOptions,
  ComboboxOption,
  Dialog,
  DialogPanel,
  DialogOverlay,
  TransitionChild,
  TransitionRoot,
} from '@headlessui/vue'

const isOpen = ref(false)
const query = ref('')
const router = useRouter()
const store = useStore()
const trans = computed(() => store.getters['config/trans'])
const search = computed(() => store.getters['search/index'])
const filteredSearch = computed(() =>
  query.value === ''
    ? []
    : search.value.filter((item) => {
        return item.name.toLowerCase().includes(query.value.toLowerCase())
      })
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
