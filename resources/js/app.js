import "../scss/app.scss"
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import AppLayout from './App.vue'
import store from './Store'
import vueTheMask from 'vue-the-mask'

import 'vuetify/styles'
import { createVuetify } from 'vuetify'

const vuetify = createVuetify({})

createInertiaApp({
  resolve: name => resolvePageComponent(
    `./Pages/${name}.vue`,
    import.meta.glob('./Pages/**/*.vue')
  ).then(module => {
    module.default.layout = module.default.layout || AppLayout
    return module
  }),
  setup({ el, App, props, plugin }) {
    
    const app = createApp({ render: () => h(App, props) })

    app.use(plugin)
    app.use(vuetify)
    app.use(store)
    app.use(vueTheMask)

    app.mount(el)
  },
})


// import "../scss/app.scss"
// import { createApp, h } from 'vue'
// import { createInertiaApp } from '@inertiajs/inertia-vue3'
// import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
// import AppLayout from './App.vue'
// import store from './Store'

// import 'vuetify/styles'
// import { createVuetify } from 'vuetify'

// const vuetify = createVuetify({})

// createInertiaApp({
//   resolve: name => resolvePageComponent(
//     `./Pages/${name}.vue`,
//     import.meta.glob('./Pages/**/*.vue')
//   ).then(module => {
//     module.default.layout = module.default.layout || AppLayout
//     return module
//   }),
//   setup({ el, App, props, plugin }) {
//     createApp({ render: () => h(App, props) })
//       .use(plugin)
//       .use(vuetify)
//       .use(store)
//       .mount(el)
//   },
// })