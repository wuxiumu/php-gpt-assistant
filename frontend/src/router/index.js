import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    { path: '/', component: () => import('@/views/Login.vue') },
    { path: '/register', component: () => import('@/views/Register.vue') },
    { path: '/chat', component: () => import('@/views/Chat.vue') },
    { path: '/profile', component: () => import('@/views/Profile.vue') },
    { path: '/stat', component: () => import('@/views/TokenStat.vue') }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

// 权限跳转
router.beforeEach((to, from, next) => {
    const publicPages = ['/', '/register']
    const token = localStorage.getItem('token')
    if (!publicPages.includes(to.path) && !token) {
        return next('/')
    }
    // 已登录时不能访问登录/注册
    if (token && publicPages.includes(to.path)) {
        return next('/chat')
    }
    next()
})

export default router