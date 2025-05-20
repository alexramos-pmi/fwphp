import "../scss/app.scss"
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import AppLayout from './App.vue'
import 'vuetify/styles' // import styles Vuetify
import { createVuetify } from 'vuetify'

const vuetify = createVuetify({})

createInertiaApp({
  resolve: name => import(`./Pages/${name}.vue`).then(module => {
    module.default.layout = module.default.layout || AppLayout
    return module
  }),
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(vuetify)
      .mount(el)
  },
})