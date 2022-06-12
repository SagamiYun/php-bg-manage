import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'

import ElementUI from 'element-ui';
import * as echarts from 'echarts'

import '@/assets/css/global.css'

Vue.config.productionTip = false

Vue.use(ElementUI
    , {
        // locale,       //国际化组件引入
        size: 'small'
    }
)

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app')

Vue.prototype.$echarts = echarts
