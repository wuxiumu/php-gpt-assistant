<template>
  <div v-if="loading" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="text-white text-xl">加载中...</div>
  </div>
</template>
<script setup>
import { storeToRefs } from 'pinia'
import { useLoading } from '../store/loading'
const { loading } = storeToRefs(useLoading())
</script>