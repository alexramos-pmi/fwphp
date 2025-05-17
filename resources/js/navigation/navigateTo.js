import { defineAsyncComponent } from 'vue'
import { PageComponent, propsData } from '../state'
import emitter from '../eventBus' // import seu emitter de eventos
import Store from "@/Store"

export async function navigateTo(href) {
  emitter.emit('navigate:start') // 👈 mostra preloader

  try {
    const response = await fetch(href, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })

    // 🔥 Outros erros HTTP
    if (!response.ok) {
      const errorText = await response.text()
      console.error(`Erro HTTP ${response.status}:`, errorText)
      // Aqui você pode mostrar uma mensagem global de erro se quiser
      return
    }

    const html = await response.text()
    const parser = new DOMParser()
    const doc = parser.parseFromString(html, 'text/html')
    const app = doc.getElementById('app')

    if (app) {
      const name = app.dataset.page
      const newProps = JSON.parse(app.dataset.props || '{}')

      const pages = import.meta.glob('../pages/**/*.vue')
      const path = `../pages/${name}.vue`

      if (!pages[path]) {
        throw new Error(`Página não encontrada: ${path}`)
      }

      const component = await pages[path]()

      PageComponent.value = defineAsyncComponent(() => Promise.resolve(component.default))
      propsData.value = newProps

      history.pushState({}, '', href)
      document.title = doc.querySelector('title')?.innerText || ''
    }
  } catch (error) {
    console.error('Erro ao navegar:', error)
  } finally {
    emitter.emit('navigate:finish') // 👈 esconde preloader
  }
}