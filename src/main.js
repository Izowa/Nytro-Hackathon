import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import vuetify from './plugins/vuetify'
import VueMeta from 'vue-meta'

Vue.config.productionTip = false

Vue.use(VueMeta, {
  'http-equiv': "Content-Security-Policy",
   content: "upgrade-insecure-requests"
})

new Vue({
  router,
  store,
  vuetify,
  render: h => h(App)
}).$mount('#app')
