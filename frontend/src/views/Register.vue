<template>
  <div class="max-w-xs mx-auto pt-24">
    <h2 class="text-xl font-bold mb-4">注册</h2>
    <form @submit.prevent="register">
      <input v-model="name" type="text" placeholder="昵称" class="input mb-2 w-full border p-2 rounded" />
      <input v-model="email" type="email" placeholder="邮箱" class="input mb-2 w-full border p-2 rounded" />
      <input v-model="password" type="password" placeholder="密码" class="input mb-2 w-full border p-2 rounded" />
      <input v-model="password_confirmation" type="password" placeholder="重复密码" class="input mb-4 w-full border p-2 rounded" />
      <button type="submit" class="w-full bg-blue-600 text-white rounded py-2">注册</button>
    </form>
    <div class="mt-4 text-sm">
      已有账号？<router-link to="/" class="text-blue-600 underline">登录</router-link>
    </div>
    <div v-if="error" class="mt-2 text-red-500">{{ error }}</div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../api/request'

const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')
const error = ref('')
const router = useRouter()

async function register() {
  error.value = ''
  try {
    await api.post('/register', {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: password_confirmation.value
    })
    router.push('/')
  } catch (e) {
    error.value = e.response?.data?.error || '注册失败'
  }
}
</script>