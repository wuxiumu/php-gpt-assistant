import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useLoading = defineStore('loading', () => {
    const loading = ref(false)
    function show() { loading.value = true }
    function hide() { loading.value = false }
    return { loading, show, hide }
})