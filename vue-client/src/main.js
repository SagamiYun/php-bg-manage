import Vue from 'vue'
import App from './App.vue'
import router from './router'
import './plugins/element.js'

import ElementUI from 'element-ui';

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
  render: h => h(App)
}).$mount('#app')