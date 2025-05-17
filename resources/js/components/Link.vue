<template>
  <a :href="props.href" @click="navigate">
    <slot />
  </a>
</template>

<script setup>

import { defineAsyncComponent } from 'vue'
import { PageComponent, propsData } from '../state'
import { navigateTo } from '@/navigation/navigateTo'

const props = defineProps({
  href: String,
  page: String,
  props: Object
})

async function navigate(event) {
  event.preventDefault()

  await navigateTo(props.href)
}
</script>