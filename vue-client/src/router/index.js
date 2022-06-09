import Vue from 'vue'
import VueRouter from 'vue-router'
import LoginView from "@/views/LoginView.vue";
import Test from "@/views/Test.vue";
import Layout from "@/layout/Layout.vue";
import {activeRouter} from "@/untils/permission.js";

Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        name: 'Layout',
        component: Layout,
        redirect: "/home",
        children: [
            {
                path: 'home',
                name: 'Home',
                component: () => import("@/views/Home"),
            }
        ]
    },
    {
        path: '/login',
        component: LoginView
    },
    {
        path: '/test',
        component: Test
    }
]

const router = new VueRouter({
    routes
})

// 在刷新页面的时候重置当前路由
activeRouter()

router.beforeEach((to, from, next) => {
    if (to.path === '/login' || to.path === '/register') {
        next()
        return
    }
    let token = sessionStorage.getItem('token')
    // ? JSON.parse(sessionStorage.getItem('token')) : {}
    if (!token || token === '' || token === null || token === 'undefined') {
        next('/login')
    } else {
        next()
    }
})


export default router
