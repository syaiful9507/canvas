<template>
  <div class="min-h-full">
    <PageHeader></PageHeader>
    <RouterView :key="$route.query"></RouterView>
  </div>
</template>

<script setup>
import PageHeader from '@/components/PageHeader'
</script>
