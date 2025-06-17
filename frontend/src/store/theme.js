import { defineStore } from 'pinia'
import { ref, watch } from 'vue'

export const useTheme = defineStore('theme', () => {
    const theme = ref(localStorage.getItem('theme') || 'light')

    function toggleTheme() {
        theme.value = theme.value === 'dark' ? 'light' : 'dark'
        localStorage.setItem('theme', theme.value)
        updateHtmlClass()
    }

    function updateHtmlClass() {
        const html = document.documentElement
        if (theme.value === 'dark') {
            html.classList.add('dark')
        } else {
            html.classList.remove('dark')
        }
    }

    // 组件加载时同步一次
    updateHtmlClass()

    // 监听 theme 变化
    watch(theme, updateHtmlClass)

    return { theme, toggleTheme }
})