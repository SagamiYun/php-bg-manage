import Vue from 'vue'
import VueRouter from 'vue-router'
import LoginView from "@/views/LoginView.vue";
import Test from "@/views/Test.vue";

Vue.use(VueRouter)

const routes = [
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

export default router
