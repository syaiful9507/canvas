<template>
  <div class="py-6">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 md:px-8">
      <div class="mt-2 md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
          <h2
            class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight dark:text-white"
          >
            {{ trans.settings }}
          </h2>
        </div>
      </div>
      <div class="py-4">
        <div
          class="h-96 rounded-lg border-4 border-dashed border-gray-200 dark:border-gray-600"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useStore } from 'vuex'

const store = useStore()
const trans = computed(() => store.getters['config/trans'])
</script>
