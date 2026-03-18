import { createRouter, createWebHistory } from 'vue-router'

import Login from '../pages/Login.vue'
import Register from '../pages/Register.vue'
import Attendance from '../pages/Attendamce.vue'
import History from '../pages/History.vue'
import Calender from '../pages/Calender.vue'

const routes = [
    {
        path: '/',
        name: 'login',
        component: Login
    },
    {
        path: '/register',
        name: 'register',
        component: Register
    },
    {
        path: '/attendance',
        name: 'attendance',
        component: Attendance
    },
    {
        path: '/history',
        name: 'history',
        component: History
    },
    {
        path: '/calender',
        name: 'calender',
        component: Calender
    }

]

const router = createRouter({
    history: createWebHistory(),
    routes
})


router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token')
    const publicRoutes = ['login', 'register']

    if (!publicRoutes.includes(to.name) && !token) {
        next({ name: 'login' })  
    } else {
        next()
    }
})


export default router