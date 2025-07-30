<template>

<v-container>
    <!-- Modal -->
    <v-dialog
        v-model="store.state.modal" max-width="700px"
        persistent
    >
        <v-card>

            <v-card-title class="text-h5"> {{ store.state.user.id > 0 ? 'Editar' : 'Adicionar' }} Usuário </v-card-title>

            <v-card-text>

                <form @submit.prevent="save">

                    <div class="modal-body">

                        <div class="card-body">

                            <v-row dense>

                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="store.state.user.name"
                                        label="Nome"
                                        variant="outlined"
                                    >
                                        <template #label>
                                            <Label
                                                style="font-weight: normal;"
                                                label="Nome"
                                            />
                                        </template>
                                    </v-text-field>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="store.state.user.email"
                                        label="E-mail"
                                        variant="outlined"
                                    >
                                        <template #label>
                                            <Label
                                                label="E-mail"
                                            />
                                        </template>
                                    </v-text-field>
                                </v-col>

                                <v-col cols="12" md="6">
                                    <v-autocomplete
                                        v-model="store.state.user.level"
                                        variant="outlined"
                                        :items="store.state.levels"
                                        item-value="id"
                                        item-title="name"
                                        @update:modelValue="changeLevel"
                                    >
                                        <template #label>
                                            <Label
                                                label="Nível"
                                            />
                                        </template>
                                    </v-autocomplete>
                                </v-col>   
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        label="Senha"
                                        type="password"
                                        v-model="store.state.user.password"
                                        variant="outlined"
                                    >
                                        <template #label v-if="store.state.user.id <= 0">
                                            <Label
                                                label="Senha"
                                            />
                                        </template>
                                    </v-text-field>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-file-input
                                        label="Foto"
                                        v-model="file"
                                        prepend-inner-icon="mdi-camera"
                                        prepend-icon=""
                                        variant="outlined"
                                    ></v-file-input>
                                </v-col>
                            </v-row>

                        </div>
                        <!-- /.card-body -->

                    </div>

                    <div class="modal-footer justify-content-end" style="display: flex; gap: 15px;">
                        <v-btn color="grey" text @click="close">Fechar</v-btn>
                        <v-btn color="green" type="submit">Salvar</v-btn>
                    </div>

                </form>
            </v-card-text>

        </v-card>

    </v-dialog>
</v-container>

</template>

<script setup>

import { ref } from 'vue';
import { useStore } from 'vuex'
import session from 'js-cookie'

import model from "@/Modules/model.js"
import clear from "@/Modules/clear.js"
import Label from "@/Components/Label.vue"
import { Inertia } from '@inertiajs/inertia'

//Define as propriedades vindas do php
const props = defineProps({
  level: {type: String, required: true},
})

//Instancia um vuex
const store = useStore()

//Declara variáveis/constantes
const file = ref(null)

levels(props.level)

//Métodos/Funções

function save(){

    store.commit('updateStateProperty', {
        objectName: 'loading',
        value: true
    });

    let id = store.state.user.id
    let route = 'usuarios'

    let formData = new FormData();

    formData.append('name', store.state.user.name)
    formData.append('email', store.state.user.email)
    formData.append('password', store.state.user.password)
    formData.append('level', store.state.user.level ? store.state.user.level : '')
    formData.append('cover', store.state.user.cover ? store.state.user.cover : '')
    formData.append('file', file.value ? file.value : '')

    if(id <= 0){

        route = `${route}/store`

        model.apiPost(route, formData).then(response => {

            store.commit('updateStateProperty', {
                objectName: 'loading',
                value: false
            });

            message('success', response.data.success)

            Inertia.visit(`${store.state.urlBase}/usuarios`)

        }).catch(error => {

            store.commit('updateStateProperty', {
                objectName: 'loading',
                value: false
            });

            message('error', error.response.data.error)
        })
    }
    else{

        route = `${route}/update`

        model.apiPut(route, id, formData).then(response => {

            store.commit('updateStateProperty', {
                objectName: 'loading',
                value: false
            });

            message('success', response.data.success)

            Inertia.visit(`${store.state.urlBase}/usuarios`)

        }).catch(error => {

            store.commit('updateStateProperty', {
                objectName: 'loading',
                value: false
            });

            message('error', error.response.data.error)
        })
    }
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

function levels(level){

    if(level < 4){

        store.commit('updateStateProperty', {
            objectName: 'levels',
            value: store.state.levels.filter(item => item.id <= level),
            mode: 'replace',
        })
    }
}

function close(){

    clear.user()
}
</script>