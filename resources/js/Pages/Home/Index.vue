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

<script>

import Link from '@/components/Link.vue'
//import { navigateTo } from '@/navigation/navigateTo'
import Layout from '@/pages/Layout.vue'

export default {
    name: "Index",
    components: {
        Link,
        Layout
    },
    props: [
        //
    ],
    data(){
        return{
            //
        }
    },
    methods: {

      userTo(){

        //navigateTo(`${this.$store.state.urlBase}/usuarios`)
      }
    }
}
</script>