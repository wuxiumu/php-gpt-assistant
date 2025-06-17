<template>
  <div class="max-w-xl mx-auto pt-8">
    <h2 class="text-xl font-bold mb-4">GPT 聊天</h2>

    <!-- 系统Prompt与模型选择 -->
    <div class="flex gap-2 mb-4">
      <select v-model="model" class="border p-1 rounded">
        <option value="gpt-3.5-turbo">GPT-3.5</option>
        <option value="gpt-4">GPT-4</option>
      </select>
      <input
          v-model="systemPrompt"
          class="flex-1 border p-1 rounded"
          placeholder="系统提示词，如：你是严谨面试官"
      />
    </div>

    <!-- 聊天输入 -->
    <div class="mb-4">
      <form @submit.prevent="send">
        <textarea
            v-model="prompt"
            rows="3"
            class="w-full border p-2 rounded"
            placeholder="输入你的问题..."
        />
        <button
            type="submit"
            class="mt-2 w-full bg-blue-600 text-white rounded py-2"
            :disabled="loading"
        >{{ loading ? "发送中..." : "发送" }}</button>
      </form>
      <div v-if="error" class="text-red-500 text-sm mt-2">{{ error }}</div>
    </div>

    <!-- 聊天历史 -->
    <div class="mb-8">
      <div v-for="item in history" :key="item.id" class="mb-6">
        <div class="font-bold">你：</div>
        <div class="bg-gray-100 rounded p-2 mb-2 whitespace-pre-line">{{ item.prompt }}</div>
        <div class="font-bold">GPT：</div>
        <div class="bg-blue-50 rounded p-2 whitespace-pre-line">{{ item.response }}</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../api/request'
import { useRouter } from 'vue-router'

const prompt = ref('')
const systemPrompt = ref('你是专业的AI助手，请简洁回答问题。') // 默认系统提示词
const model = ref('gpt-3.5-turbo')
const history = ref([])
const loading = ref(false)
const error = ref('')
const router = useRouter()

async function send() {
  if (!prompt.value.trim()) return
  error.value = ''
  const q = prompt.value
  prompt.value = ''
  loading.value = true
  try {
    const res = await api.post('/chat', {
      message: q,
      model: model.value,
      system_prompt: systemPrompt.value
    })
    history.value.unshift({
      id: Date.now(),
      prompt: q,
      response: res.reply
    })
  } catch (e) {
    if (e.response?.status === 401) {
      localStorage.removeItem('token')
      router.push('/')
    } else {
      error.value = e.response?.data?.error || '发送失败'
    }
  }
  loading.value = false
}

async function fetchHistory() {
  try {
    const res = await api.get('/chat/history')
    // 这里假设返回数组结构
    history.value = res
  } catch (e) {}
}

onMounted(fetchHistory)
</script>