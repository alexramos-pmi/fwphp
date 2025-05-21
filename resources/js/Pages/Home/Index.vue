<template>
  <Layout>
    <v-app>
      <v-main>
        <v-container class="text-center">
          <h1>Página Home</h1>
          <v-btn color="primary" @click="userTo">Ir para usuários</v-btn>
        </v-container>
      </v-main>
    </v-app>
  </Layout>
</template>

<script setup>

import Layout from '@/Pages/Layout.vue'
import { Inertia } from '@inertiajs/inertia'

function userTo(){

  Inertia.visit(`${store.state.urlBase}/usuarios`)
}
</script>