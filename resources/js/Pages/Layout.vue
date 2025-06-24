<template>
    <v-app style="background-color: rgb(238, 240, 242);">

        <div id="preloader" v-if="store.state.loading" style="z-index: 9999;">
            <span class="loader"></span>
        </div>

      <!-- Barra lateral com animação -->
      <v-navigation-drawer app v-model="store.state.drawer" clipped temporary>
        <v-list dense>
            <ul class="menu">

                <li class="nav-item" v-for="(item, index) in store.state.menus" :key="index">

                    <Link :href="`${store.state.urlBase}/${item.route}`" class="nav-link" v-if="!item.children">

                        <div class="item" :style="item.title === 'Sair' ? 'color: #FA8072;' : ''">
                            <v-icon>{{ item.icon }}</v-icon>
                            <span>
                                {{ item.title }}
                            </span>
                        </div>

                    </Link>

                    <a href="#" class="nav-link" v-else>
                        <div class="item">
                            <v-icon>{{ item.icon }}</v-icon>
                            <span>
                                {{ item.title }}
                            </span>
                        </div>
                    </a>

                    <ul class="menu" v-if="item.children">

                        <li class="nav-item" v-for="(subitem, subindex) in item.children" :key="subindex">

                            <Link :href="`${store.state.urlBase}/${subitem.route}`" class="nav-link">
                                <div class="item">
                                    <v-icon>{{ subitem.icon }}</v-icon>
                                    <span>
                                        {{ subitem.title }}
                                    </span>
                                </div>
                            </Link>

                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                  <a href="javascript:void(0)" @click="logout">
                    <div class="item" style="color: #FA8072;">
                        <v-icon>mdi-exit-to-app</v-icon>
                        <span>
                            Sair
                        </span>
                    </div>
                  </a>
                </li>
            </ul>
        </v-list>
      </v-navigation-drawer>

      <!-- Cabeçalho fixo -->
      <v-app-bar app style="padding: 0 10px;">

        <img :src="`${store.state.urlBase}/img/logo.png`" style="width: 50px; cursor: pointer;" @click="goHome" alt="">
        <v-toolbar-title>{{ appName }}</v-toolbar-title>

        <!-- Ícones de notificação e usuário -->
        <v-btn icon>
          <v-icon>mdi-bell-outline</v-icon>
        </v-btn>

        <v-btn icon>
          <v-icon>mdi-email-outline</v-icon>
        </v-btn>

        <v-btn icon>
          <v-avatar>
            <img :src="session.get('_usercover') !== 'null' ? `${store.state.urlBase}/images/${session.get('_usercover')}` : `${store.state.urlBase}/img/image-default.jpg`" alt="User Avatar" style="width: 40px; height: 40px;">
          </v-avatar>
        </v-btn>

        <!-- Botão do menu à esquerda da logo -->
        <v-btn icon @click="toggleDrawer">
          <v-icon>mdi-menu</v-icon>
        </v-btn>

      </v-app-bar>

      <!-- Conteúdo principal -->
      <v-main>
        <v-container fluid>
          <slot></slot>
        </v-container>
      </v-main>
    </v-app>

    <Message />

  </template>

<script setup>

import { useStore } from 'vuex'

import model from "@/Modules/model.js"
import Message from '@/components/Message.vue'
import session from 'js-cookie'
import { Inertia } from '@inertiajs/inertia'
import { Link } from '@inertiajs/inertia-vue3'

const appName = document.title

//Instancia vuex
const store = useStore()

loading()
openMenu()

//Funções/Métodos

function goHome(){

  Inertia.visit(`${store.state.urlBase}`)
}

function loading(){

  Inertia.on('start', () => {

      store.commit('updateStateProperty', {
          objectName: 'loading',
          value: true
      });
  })

  Inertia.on('finish', () => {

      store.commit('updateStateProperty', {
          objectName: 'loading',
          value: false
      });
  })
}

function toggleDrawer() {

  store.commit('updateStateProperty', {
    objectName: 'drawer',
    value: !store.state.drawer
  });
}

function logout(){

  let formData = new FormData()
  formData.append('message', 'Você saiu, volte sempre!')

  model.apiPost('logout', formData).then(response => {

    //Limpa os cookies
    session.remove('_userid')
    session.remove('_username')
    session.remove('_useremail')
    session.remove('_userlevel')
    session.remove('_usercover')
    session.remove('_userlevelname')

    //Gera uma mensagem na tela
    store.commit('updateStateProperty', {
      objectName: 'snackbar',
      value: {
          type: 'success',
          message: response.data.success,
          status: true
      },
      mode: 'replace'
    })

    window.location.href = `${store.state.urlBase}/login`
    //Inertia.visit(`${store.state.urlBase}/login`)
  })
}

function openMenu(){

  model.apiGet('menus').then(response => {

    store.commit('updateStateProperty', {
      objectName: 'menus',
      value: response.data.menu,
      mode: 'replace'
    });
  });
}
</script>

  <style scoped>
  /* Estilos para a navegação lateral */
  .v-navigation-drawer {
    width: 260px; /* Aumentei a largura do menu */
    transition: transform 0.3s ease;
  }

  .v-navigation-drawer .v-list-item {
    padding: 12px 16px;
    border-radius: 4px;
    transition: background-color 0.3s;
    display: flex;
    align-items: center; /* Alinha ícones e texto horizontalmente */
  }

  .v-navigation-drawer .v-list-item:hover {
    background-color: #f5f5f5;
  }

  .v-navigation-drawer .v-list-item-content {
    margin-left: 16px; /* Aumentei o espaçamento entre o ícone e o texto */
  }

  .v-navigation-drawer .v-list-item-icon {
    min-width: 40px;
  }

  .search-field {
    width: 300px;
  }

  /* Responsividade para dispositivos móveis */
  @media (max-width: 960px) {
    .v-navigation-drawer {
      width: 200px;
    }

    .search-field {
      width: 100%;
    }
  }
  </style>