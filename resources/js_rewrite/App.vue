<template>
    <page-header></page-header>
    <router-view></router-view>
</template>

<script>
import PageHeader from "./components/PageHeader";

export default {
    components: {
        PageHeader
    }
}
</script>
