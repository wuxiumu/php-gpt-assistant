<template>
  <div>
    <input type="file" accept="image/*" @change="onFileChange" />
    <div v-if="imageUrl" class="mt-4">
      <cropper
          ref="cropperRef"
          :src="imageUrl"
          :aspect-ratio="1"
          :view-mode="1"
          class="w-64 h-64"
      />
      <button @click="getCropped" class="mt-2 bg-blue-600 text-white px-3 py-1 rounded">裁剪并上传</button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import Cropper from 'vue-cropperjs'
import 'cropperjs/dist/cropper.css'
import api from '../api/request'

const imageUrl = ref('')
const cropperRef = ref(null)

function onFileChange(e) {
  const file = e.target.files[0]
  if (!file) return
  imageUrl.value = URL.createObjectURL(file)
}

async function getCropped() {
  const canvas = cropperRef.value?.cropper.getCroppedCanvas({
    width: 256,
    height: 256,
    imageSmoothingQuality: 'high'
  })
  if (!canvas) return
  canvas.toBlob(async (blob) => {
    // 这里可以选择用 blob 上传到 OSS 或后端
    const formData = new FormData()
    formData.append('avatar', blob, 'avatar.png')
    try {
      // 如果走本地后端上传
      await api.post('/user/avatar', formData, { headers: { 'Content-Type': 'multipart/form-data' } })
      alert('上传成功')
    } catch {
      alert('上传失败')
    }
  }, 'image/png')
}
</script>