<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
    <!-- 顶部导航 -->
    <header class="bg-white dark:bg-gray-800 shadow-sm px-4 py-3 flex items-center justify-between">
      <h1 class="text-lg font-bold tracking-wide">php-gpt-assistant</h1>
      <nav class="flex items-center gap-6">
        <router-link to="/chat" class="hover:underline" active-class="text-blue-600 font-semibold">聊天</router-link>
        <router-link to="/stat" class="hover:underline" active-class="text-blue-600 font-semibold">Token统计</router-link>
        <router-link v-if="isLogin" to="/profile" class="hover:underline" active-class="text-blue-600 font-semibold">设置</router-link>
        <router-link v-else to="/" class="hover:underline" active-class="text-blue-600 font-semibold">登录</router-link>
        <button v-if="isLogin" @click="logout" class="text-xs text-gray-400 hover:text-red-500 ml-2">退出</button>
        <!-- 主题切换按钮 -->
        <button
            class="ml-3 p-1 rounded-full border hover:bg-gray-200 dark:hover:bg-gray-700"
            @click="toggleTheme"
            :title="theme === 'dark' ? '切换到浅色' : '切换到深色'"
        >
          <span v-if="theme === 'dark'">🌙</span>
          <span v-else>☀️</span>
        </button>
      </nav>
    </header>
    <!-- 全局 loading/消息提示 -->
    <GlobalLoading />
    <GlobalMsg />
    <!-- 主内容区 -->
    <main class="max-w-2xl mx-auto px-2 py-6">
      <router-view />
    </main>
    <!-- 页脚 -->
    <footer class="py-4 text-center text-xs text-gray-400">
      &copy; {{ year }} php-gpt-assistant. Powered by Vue3 + Vite.
    </footer>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useTheme } from './store/theme'
import GlobalLoading from './components/GlobalLoading.vue'
import GlobalMsg from './components/GlobalMsg.vue'

// 主题
const { theme, toggleTheme } = useTheme()

// 登录状态
const isLogin = computed(() => !!localStorage.getItem('token'))
const router = useRouter()
function logout() {
  localStorage.removeItem('token')
  router.push('/')
}

// 年份
const year = new Date().getFullYear()
</script>