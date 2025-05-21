<template>
    <v-container>
        <!-- Modal -->
        <v-dialog
            v-model="$store.state.modalDel" max-width="500px"
            persistent
        >

            <v-card>

                <v-card-title class="text-h5"> Excluir Usuário </v-card-title>

                <v-card-text>

                    <p class="p-body">Tem certeza que deseja excluir <b>{{ $store.state.user.name }}</b>?</p>

                    <div class="modal-footer justify-content-end" style="display: flex; gap: 15px;">
                        <v-btn color="grey" text @click="close">Não</v-btn>
                        <v-btn color="green" @click="del">Sim</v-btn>
                    </div>

                </v-card-text>

            </v-card>

        </v-dialog>
    </v-container>
</template>

<script setup>

import { useStore } from 'vuex'

import model from "@/Modules/model.js"
import clear from "@/Modules/clear.js"
import { navigateTo } from '@/navigation/navigateTo'

//Instancia vuex
const store = useStore()

function del(){

    store.commit('updateStateProperty', {
        objectName: 'loading',
        value: true
    });

    let id = store.state.user.id

    model.apiDel('usuarios/delete', id).then(response => {

        store.commit('updateStateProperty', {
            objectName: 'loading',
            value: false
        });

        navigateTo(`${store.state.urlBase}/usuarios`)

        message('success', response.data.success)

    }).catch(error => {

        store.commit('updateStateProperty', {
            objectName: 'loading',
            value: false
        });

        message('error', error.response.data.error)
    })
}

function message(type, body){

    store.commit('updateStateProperty', {
        objectName: 'snackbar',
        value: {
            type: type,
            message: body,
            status: true
        },
        mode: 'replace'
    })

    if(type === 'success'){

        close()
    }
}

function close(){

    clear.user()
}
</script>

<style scoped lang="scss">

@use "@scss/variables" as *;

.p-body{
    margin-bottom: 20px;
}
</style>
