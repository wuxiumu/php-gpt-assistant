<template>
  <div v-if="msg" class="fixed top-4 left-1/2 -translate-x-1/2 z-50 px-4 py-2 rounded shadow-lg text-white"
       :class="{
      'bg-green-500': msgType === 'success',
      'bg-blue-500': msgType === 'info',
      'bg-red-500': msgType === 'error',
    }">
    {{ msg }}
  </div>
</template>
<script setup>
import { storeToRefs } from 'pinia'
import { useGlobal } from '../store/global'
const { msg, msgType } = storeToRefs(useGlobal())
</script>