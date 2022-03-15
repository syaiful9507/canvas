<template>
    <div class="min-h-full">
        <page-header></page-header>
        <router-view></router-view>
    </div>
</template>

<script>
import PageHeader from "@/components/PageHeader.vue";
import { defineComponent } from "vue";

export default  defineComponent({
    components: {
        PageHeader
    }
})
</script>
