<template>

    <span>
        <span class="red--text">*</span> {{ props.label }}
    </span>

</template>

<script setup>

import { useStore } from 'vuex'

//Instancia um vuex
const store = useStore()

//Define as propriedades vindas do php
const props = defineProps({
  label: {type: String, required: true}
})

</script>
