<template>
    <page-header></page-header>
    <router-view></router-view>
</template>

<script lang="ts">
import PageHeader from "./components/PageHeader.vue";
import { defineComponent } from "vue";

export default defineComponent({
    name: 'app',
    components: {
        PageHeader
    }
});
</script>
