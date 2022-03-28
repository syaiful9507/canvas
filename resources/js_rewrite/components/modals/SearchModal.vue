<template>
   <TransitionRoot :show="open" as="template" @after-leave="query = ''">
       <Dialog as="div" class="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 md:p-20" @close="open = false">
           <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
               <DialogOverlay class="fixed inset-0 bg-gray-500 bg-opacity-25 transition-opacity" />
           </TransitionChild>

           <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="ease-in duration-200" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
               <Combobox as="div" class="mx-auto max-w-xl transform divide-y divide-gray-100 overflow-hidden rounded-xl bg-white shadow-2xl ring-1 ring-black ring-opacity-5 transition-all" @update:modelValue="onSelect">
                   <div class="relative">
                       <SearchIcon class="pointer-events-none absolute top-3.5 left-4 h-5 w-5 text-gray-400" aria-hidden="true" />
                       <ComboboxInput class="h-12 w-full border-0 bg-transparent pl-11 pr-4 text-gray-800 placeholder-gray-400 focus:ring-0 sm:text-sm" :placeholder="trans.search_canvas" @change="query = $event.target.value" />
                   </div>

                   <ComboboxOptions v-if="filteredSearch.length > 0" static class="max-h-72 scroll-py-2 overflow-y-auto py-2 text-sm text-gray-800">
                       <ComboboxOption v-for="items in filteredSearch" :key="items.item.id" :value="items.item" as="template" v-slot="{ active }">
                           <li :class="['cursor-pointer select-none px-4 py-2', active && 'bg-indigo-600 text-white']">
                               {{ items.item.title }}
                           </li>
                       </ComboboxOption>
                   </ComboboxOptions>

                   <p v-if="query !== '' && filteredSearch.length === 0" class="p-4 text-sm text-gray-500">No people found.</p>
               </Combobox>
           </TransitionChild>
       </Dialog>
   </TransitionRoot>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue'
import { useStore } from 'vuex';
import { SearchIcon } from '@heroicons/vue/solid'
import Fuse from 'fuse.js';
import {
  Combobox,
  ComboboxInput,
  ComboboxOptions,
  ComboboxOption,
  Dialog,
  DialogOverlay,
  TransitionChild,
  TransitionRoot,
} from '@headlessui/vue'

const store = useStore()
const trans = computed(() => store.getters["settings/trans"])
const search = computed(() => store.getters["search/index"])

// works
// const posts = [{"id":"015ff504-bc4e-49e6-b5d4-2c26557fcf81","title":"I Ran to Escape My Problems. Then Running Became One.","name":"I Ran to Escape My Problems. Then Running Became One.","type":"Post","route":"edit-post","estimated_read_time_in_minutes":0},{"id":"d118281a-0593-485e-a7e6-248b2fe18572","title":"Being Happy Is Hard Work","name":"Being Happy Is Hard Work","type":"Post","route":"edit-post","estimated_read_time_in_minutes":0},{"id":"401b7e4f-bf3e-44ea-a226-877046320994","title":"The Architect of Tomorow","name":"The Architect of Tomorow","type":"Post","route":"edit-post","estimated_read_time_in_minutes":0},{"id":"afc7362a-f168-4271-be23-3d24e15f36ce","title":"Here\u2019s How Robots Can Help Us Deal With Pollution","name":"Here\u2019s How Robots Can Help Us Deal With Pollution","type":"Post","route":"edit-post","estimated_read_time_in_minutes":0},{"id":"421a03b1-8742-42e0-aa7e-061d240a513e","title":"How Facebook Borrows From the NSA Playbook","name":"How Facebook Borrows From the NSA Playbook","type":"Post","route":"edit-post","estimated_read_time_in_minutes":0},{"id":"6e227148-20f6-4ad8-9d98-bea906674c8c","title":"A New Vision for the Future of Driving","name":"A New Vision for the Future of Driving","type":"Post","route":"edit-post","estimated_read_time_in_minutes":0}];
// const fuse = new Fuse(posts, options);

// doesn't work
const fuse = new Fuse(search.value, {  
    isCaseSensitive: false,
    includeScore: true,
    defaultAll: false,
    shouldSort: true,
    includeMatches: false,
    findAllMatches: false,
    minMatchCharLength: 1,
    location: 0,
    threshold: 0.6,
    distance: 100,
    useExtendedSearch: false,
    ignoreLocation: false,
    ignoreFieldNorm: false,
    fieldNormWeight: 1,
    keys: [
        "name",
    ]
  }
);

const open = ref(true);
const query = ref('');

const filteredSearch = computed(() =>
    query.value === ''
    ? []
    : fuse.search(query.value)
)

function onSelect(person) {
    window.location = person.url
}

onMounted(() => {
    store.dispatch('search/buildIndex');
});
</script>