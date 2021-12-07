<template>
    <div class="min-h-full">
        <page-header></page-header>
        <router-view></router-view>
    </div>
</template>

<script lang="ts">
import PageHeader from "@/components/PageHeader.vue";
import { defineComponent } from "vue";
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { BellIcon, MenuIcon, XIcon } from '@heroicons/vue/outline'

export default  defineComponent({
    components: {
        Disclosure,
        DisclosureButton,
        DisclosurePanel,
        Menu,
        MenuButton,
        MenuItem,
        MenuItems,
        BellIcon,
        MenuIcon,
        XIcon,
        PageHeader
    }
})
</script>
