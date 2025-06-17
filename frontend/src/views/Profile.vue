<template>
  <div class="max-w-lg mx-auto pt-16">
    <h2 class="text-xl font-bold mb-4">个人中心</h2>
    <!-- 头像上传区 -->
    <div class="mb-6">
      <div class="mb-2">头像：</div>
      <div class="mb-2">
        <img
            :src="avatarPreview || avatar || defaultAvatar"
            alt="avatar"
            class="w-20 h-20 rounded-full object-cover border"
        />
      </div>
      <input type="file" accept="image/*" @change="onAvatarChange" />
      <button
          @click="uploadAvatar"
          class="mt-2 px-4 py-1 bg-blue-600 text-white rounded"
          v-if="avatarFile"
      >上传头像</button>
    </div>
    <!-- OpenAI Key 设置... -->
    <div class="mb-6">
      <div class="mb-2">绑定你的 OpenAI API Key：</div>
      <input v-model="apiKey" type="text" class="w-full border p-2 rounded" placeholder="sk-..." />
      <button @click="setApiKey" class="mt-2 w-full bg-blue-600 text-white rounded py-2">保存</button>
      <div v-if="msg" class="mt-2 text-green-600">{{ msg }}</div>
      <div v-if="error" class="mt-2 text-red-500">{{ error }}</div>
    </div>
    <div>
      <button class="text-xs text-gray-400 underline mt-6" @click="logout">退出登录</button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../api/request'
import { useRouter } from 'vue-router'

const avatar = ref('')
const avatarPreview = ref('')
const avatarFile = ref(null)
const defaultAvatar = 'https://api.dicebear.com/7.x/thumbs/svg?seed=ai' // 随机头像

const apiKey = ref('')
const msg = ref('')
const error = ref('')
const router = useRouter()

function onAvatarChange(e) {
  const file = e.target.files[0]
  if (!file) return
  avatarFile.value = file
  // 本地预览
  const reader = new FileReader()
  reader.onload = evt => {
    avatarPreview.value = evt.target.result
  }
  reader.readAsDataURL(file)
}

async function uploadAvatar() {
  if (!avatarFile.value) return
  const formData = new FormData()
  formData.append('avatar', avatarFile.value)
  try {
    const res = await api.post('/user/avatar', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    avatar.value = res.avatar
    msg.value = '头像上传成功'
    avatarPreview.value = ''
    avatarFile.value = null
  } catch (e) {
    error.value = e.response?.data?.error || '上传失败'
  }
}

async function setApiKey() {
  msg.value = error.value = ''
  try {
    await api.post('/user/api-key', { api_key: apiKey.value })
    msg.value = '保存成功'
  } catch (e) {
    error.value = e.response?.data?.error || '保存失败'
  }
}

async function fetchUser() {
  try {
    const res = await api.get('/user')
    apiKey.value = res.api_key || ''
    avatar.value = res.avatar || ''
  } catch (e) {}
}

function logout() {
  localStorage.removeItem('token')
  router.push('/')
}

onMounted(fetchUser)
</script>