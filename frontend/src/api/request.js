import axios from 'axios'
import { useLoading } from '../store/loading'

const service = axios.create({
    baseURL: 'https://php-gpt-assistant.51dsn.com/api',
    timeout: 10000
})

service.interceptors.request.use(
    config => {
        useLoading().show()
        // 自动带上 token
        const token = localStorage.getItem('token')
        if (token) {
            config.headers.Authorization = `Bearer ${token}`
        }
        return config
    },
    err => {
        useLoading().hide()
        return Promise.reject(err)
    }
)

service.interceptors.response.use(
    res => {
        useLoading().hide()
        return res.data
    },
    err => {
        useLoading().hide()
        return Promise.reject(err)
    }
)

export default service
