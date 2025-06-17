<template>
  <div class="max-w-lg mx-auto pt-16">
    <h2 class="text-xl font-bold mb-4">Token 消耗统计</h2>
    <div v-if="loading">加载中...</div>
    <div v-else>
      <div class="mb-2">总消耗：<span class="font-bold text-blue-600">{{ stat.total }}</span> tokens</div>
      <div>
        <table class="w-full text-sm border mt-4">
          <thead class="bg-gray-100">
          <tr><th>日期</th><th>消耗</th></tr>
          </thead>
          <tbody>
          <tr v-for="row in stat.days" :key="row.day">
            <td class="p-1">{{ row.day }}</td>
            <td class="p-1">{{ row.total_tokens }}</td>
          </tr>
          </tbody>
        </table>
        <div v-if="!stat.days.length" class="text-gray-400 text-xs mt-4">暂无统计数据</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../api/request'
import { useGlobal } from '../store/global'

const loading = ref(false)
const stat = ref({ days: [], total: 0 })
const { showMsg } = useGlobal()

async function fetchStat() {
  loading.value = true
  try {
    const res = await api.get('/stat/token-usage')
    stat.value = res
  } catch (e) {
    showMsg('获取统计失败', 'error')
  }
  loading.value = false
}

onMounted(fetchStat)
</script>