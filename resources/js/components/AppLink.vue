<template>
  <a v-if="isExternalLink" v-bind="$attrs" :href="to" target="_blank">
    <slot />
  </a>
  <RouterLink
    v-else
    v-slot="{ isActive, href, navigate }"
    v-bind="$props"
    custom
  >
    <a
      v-bind="$attrs"
      :href="href"
      :class="isActive ? activeClass : inactiveClass"
      @click="navigate"
    >
      <slot />
    </a>
  </RouterLink>
</template>

<script>
import { RouterLink } from 'vue-router'
import { defineComponent } from 'vue'

export default defineComponent({
  inheritAttrs: false,
  props: {
    ...RouterLink.props,
    inactiveClass: {
      type: String,
      default: '',
    },
  },
  computed: {
    isExternalLink() {
      return typeof this.to === 'string' && this.to.startsWith('http')
    },
  },
})
</script>
