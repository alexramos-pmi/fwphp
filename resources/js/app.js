import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import AppLayout from './App.vue'

createInertiaApp({
  resolve: name => import(`./Pages/${name}.vue`).then(module => {
    module.default.layout = module.default.layout || AppLayout
    return module
  }),
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .mount(el)
  },
})