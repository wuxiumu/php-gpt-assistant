import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useGlobal = defineStore('global', () => {
    const msg = ref('')
    const msgType = ref('info')
    const msgTimeout = ref(null)

    function showMsg(message, type = 'info', duration = 2000) {
        msg.value = message
        msgType.value = type
        if (msgTimeout.value) clearTimeout(msgTimeout.value)
        msgTimeout.value = setTimeout(() => {
            msg.value = ''
        }, duration)
    }

    return { msg, msgType, showMsg }
})