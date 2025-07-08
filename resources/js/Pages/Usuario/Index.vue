<template>
<Layout>

    <v-row>
      <v-col cols="12">
        <v-card>

            <v-card-title style="display: flex; justify-content: space-between; padding: 15px;">
                <span>
                    Usuário
                </span>

                <v-btn color="green" @click="createItem" v-if="level > 1">
                    <v-icon>mdi-plus</v-icon> Usuário
                </v-btn>

            </v-card-title>

            <v-card-text>

                <!-- Campo de pesquisa -->
                <v-text-field
                    v-model="search"
                    label="Pesquisar"
                    prepend-inner-icon="mdi-magnify"
                    outlined
                    dense
                    class="mb-4"
                    variant="outlined"
                ></v-text-field>

                <v-table>
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Nível</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(item, index) in filteredItems" :key="index"
                            :class="{ 'even-row': index % 2 === 0, 'odd-row': index % 2 !== 0 }"
                        >
                            <td data-cell="Foto">
                              <img :src="item.cover ? item.path : `${store.state.urlBase}/img/image-default.jpg`" alt="" style="width: 40px; border-radius: 50%; margin-top: 7px;">
                            </td>
                            <td data-cell="Nome">{{ item.name }}</td>
                            <td data-cell="Email">{{ item.email }}</td>
                            <td data-cell="Nível">{{ item.level_name }}</td>
                            <td data-cell="Ações">
                              <span @click="editItem(item)" title="Editar">
                                  <v-icon>mdi-file-edit-outline</v-icon>
                              </span>

                              <span @click="delItem(item)" title="Excluir">
                                  <v-icon>mdi-trash-can-outline</v-icon>
                              </span>
                            </td>
                        </tr>
                    </tbody>
                </v-table>

                <!-- Paginação -->
                <v-pagination
                    v-model="page"
                    :length="totalPages"
                    class="mt-4"
                ></v-pagination>

            </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <Create 
      :ers="props.ers" 
      :els="props.els" 
      :etas="props.etas"  
      :level
    />
    
    <Delete />

</Layout>

</template>

<script setup>

import { ref, computed } from 'vue'
import { useStore } from 'vuex'
import { Inertia } from '@inertiajs/inertia'
import session from 'js-cookie'

import Layout from "@/pages/Layout.vue"
import Create from "@/pages/Usuario/Create.vue"
import Delete from "@/pages/Usuario/Delete.vue"

//Define as propriedades vindas do php
const props = defineProps({
  list: {type: Array, required: true},
})

//Instancia um vuex
const store = useStore()

//Define as propriedades, ref() refere-se às proriedades reativas (v-model)
const search = ref('')
const filtered = ref([])
const itemsPerPage = 5
const page = ref(1)
const level = session.get('_userlevel')

//Computed que filtra o array list
const filteredItems = computed(() => {
  filtered.value = props.list

  if (search.value) {
    filtered.value = props.list.filter((item) =>
      Object.values(item).some((val) =>
        String(val).toLowerCase().includes(search.value.toLowerCase())
      )
    )
  }

  return paginatedItems(filtered.value)
})

//Computed que calcula o total das páginas
const totalPages = computed(() =>
  Math.ceil(filtered.value.length / itemsPerPage)
)

//Abaixo ficam os métodos/funções

function paginatedItems(items) {
  const start = (page.value - 1) * itemsPerPage
  return items.slice(start, start + itemsPerPage)
}

function createItem() {
  store.commit('updateStateProperty', {
    objectName: 'modal',
    value: true,
  })
}

function editItem(item) {
  user(item)
  store.commit('updateStateProperty', {
    objectName: 'modal',
    value: true,
  })
}

function delItem(item) {
  user(item)
  store.commit('updateStateProperty', {
    objectName: 'modalDel',
    value: true,
  })
}

function setores(item){

    user(item)

    this.$store.commit('updateStateProperty', {
        objectName: 'modalSetor',
        value: true
    });
}

function user(item) {
  store.commit('updateStateProperty', {
    objectName: 'user',
    value: {
      id: item.id,
      name: item.name,
      email: item.email,
      password: '',
      level: item.level,
      cover: item.cover,
    },
    mode: 'replace',
  })
}

</script>

<style scoped lang="scss">

@use "@scss/variables" as *;

/* Cor para linhas pares */
.even-row {
  background-color: #f5f5f5;
}

/* Cor para linhas ímpares */
.odd-row {
  background-color: #ffffff;
}

</style>
