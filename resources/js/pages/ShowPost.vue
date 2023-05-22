<template>
    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 md:px-8">
            <div class="mt-2 md:flex md:items-center md:justify-between">
                <div v-if="post" class="min-w-0 flex-1">
                    <h2
                        class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight"
                    >
                        {{ post.title }}
                    </h2>
                </div>
            </div>
            <div class="py-4">
                <div
                    class="h-96 rounded-lg border-4 border-dashed border-gray-200"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import request from '@/utils/request'
import { useRoute } from 'vue-router'

const route = useRoute()
const post = ref(null)

onMounted(async () => {
    await fetchPost()
})

function fetchPost() {
    return request.get(`api/posts/${route.params.id}`).then(({ data }) => {
        post.value = data.post
    })
}
</script>
