<template>
    <v-snackbar
      v-model="store.state.snackbar.status"
    >
      {{ store.state.snackbar.message }}

      <template v-slot:actions>
        <v-btn
          :color="store.state.snackbar.type === 'success' ? '#00FA9A' : '#FFC0CB'"
          variant="text"
          @click="close"
        >
          Ok
        </v-btn>
      </template>
    </v-snackbar>
</template>

<script setup>

import { useStore } from 'vuex'

//Instancia um vuex
const store = useStore()

function close(){

    store.commit('updateStateProperty', {
        objectName: 'snackbar',
        value: {
            type: '',
            message: '',
            status: false
        },
        mode: 'replace'
    })
}
</script>

<style scoped lang="scss">

@use 'sass:color';
@use "@scss/variables" as *;

.box-delete{
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;

    span:nth-child(1){
        color: color.adjust($secondary, $lightness: 30%);
        font-weight: 700;
        font-size: 1.5rem;
        text-align: center;
    }
    span:nth-child(2){
        font-size: 1.1rem;
        font-style: italic;
        margin-bottom: 10px;
        color: $secondary;
    }
}
</style>
