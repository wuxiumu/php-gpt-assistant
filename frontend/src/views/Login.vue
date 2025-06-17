<template>
  <div class="max-w-xs mx-auto pt-24">
    <h2 class="text-xl font-bold mb-4">登录</h2>
    <form @submit.prevent="login">
      <input v-model="email" type="email" placeholder="邮箱" class="input mb-2 w-full border p-2 rounded" />
      <input v-model="password" type="password" placeholder="密码" class="input mb-4 w-full border p-2 rounded" />
      <button type="submit" class="w-full bg-blue-600 text-white rounded py-2">登录</button>
    </form>
    <div class="mt-4 text-sm">
      没有账号？<router-link to="/register" class="text-blue-600 underline">注册</router-link>
    </div>
    <div v-if="error" class="mt-2 text-red-500">{{ error }}</div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../api/request'

const email = ref('')
const password = ref('')
const error = ref('')
const router = useRouter()

async function login() {
  error.value = ''
  try {
    const res = await api.post('/login', {
      email: email.value,
      password: password.value
    })
    localStorage.setItem('token', res.token)
    router.push('/chat')
  } catch (e) {
    error.value = e.response?.data?.error || '登录失败'
  }
}
</script>