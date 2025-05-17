<script setup>
import { defineAsyncComponent } from 'vue'
import { PageComponent, propsData } from '@/state'

const el = document.getElementById('app')

async function loadPage(name, props) {

  const pages = import.meta.glob('./pages/**/*.vue')
  const path = `./pages/${name}.vue`

  if(!pages[path]){
    throw new Error(`Página não encontrada: ${path}`)
  }

  const component = await pages[path]()

  PageComponent.value = defineAsyncComponent(() => Promise.resolve(component.default))
  propsData.value = props
}

// Dados iniciais vindos do blade
const initialPage = el.dataset.page
const initialProps = JSON.parse(el.dataset.props || '{}')
loadPage(initialPage, initialProps)

// Navegação pelo navegador
window.addEventListener('popstate', async () => {
  const res = await fetch(location.href, {
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
  const html = await res.text()
  const parser = new DOMParser()
  const doc = parser.parseFromString(html, 'text/html')
  const app = doc.getElementById('app')

  if (app) {
    const name = app.dataset.page
    const props = JSON.parse(app.dataset.props || '{}')
    loadPage(name, props)
    document.title = doc.querySelector('title')?.innerText || ''
  }
})
</script>

<template>
  <component :is="PageComponent" v-bind="propsData" />
</template>