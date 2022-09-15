<template>
  <div class="py-6">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 md:px-8">
      <h1 class="text-2xl font-semibold text-gray-900">
        {{ trans.dashboard }}
      </h1>
    </div>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 md:px-8">
      <!-- Replace with your content -->
      <div class="py-4">
        <div class="h-96 rounded-lg border-4 border-dashed border-gray-200" />
      </div>
      <!-- /End replace -->
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useStore } from 'vuex'

const store = useStore()
const trans = computed(() => store.getters['config/trans'])
</script>
