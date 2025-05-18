<template>

    <div class="logo-container">

        <div id="preloader" v-if="store.state.loading">
            <span class="loader"></span>
        </div>

        <div class="logo-header">
            <img :src="`${store.state.urlBase}/img/logo.png`">
        </div>

        <div class="logo-body">

            <form @submit.prevent="login">

                <span>Acesse sua conta</span>

                <div class="middle">
                    <div>
                        <label>
                            <i class="fa-solid fa-envelope"></i>
                            E-mail:
                        </label>
                        <input type="text" v-model="store.state.login.email">
                    </div>

                    <div>
                        <label>
                            <i class="fa-solid fa-lock"></i>
                            Senha:
                        </label>
                        <input type="password" v-model="store.state.login.password">
                    </div>
                </div>

                <button>Acessar minha conta</button>
            </form>

            <span
                id="snackbar" :class="store.state.messages.success ? 'success' : 'error'"
            />
        </div>
    </div>

    <Message />

</template>

<script setup>

import { useStore } from 'vuex'

import model from "@/Modules/model.js"
import Message from '@/components/Message.vue'
import emitter from "@/eventBus.js"
import { navigateTo } from '@/navigation/navigateTo'
import session from 'js-cookie'

//Instancia vuex
const store = useStore()

loading()
checkLogin()

function loading(){

    emitter.on('navigate:start', () => {
          
        store.commit('updateStateProperty', {
        objectName: 'loading',
        value: true
        });
    })

    emitter.on('navigate:finish', () => {
        store.commit('updateStateProperty', {
        objectName: 'loading',
        value: false
        });
    })
}

function checkLogin(){

    model.apiGet('login/check').then(response => {

        let check = response.data.check

        if(check){

            navigateTo(`${store.state.urlBase}`)
        }
    })
}

function login(){

    store.commit('updateStateProperty', {
        objectName: 'loading',
        value: true
    });

    let loginData = new FormData();

    loginData.append('email', store.state.login.email)
    loginData.append('password', store.state.login.password)

    model.apiPost('login', loginData).then(response => {

        store.commit('updateStateProperty', {
                objectName: 'loading',
                value: false
            });

        message('success', response.data.success)

        session.set('_userid', response.data.user.userid)
        session.set('_username', response.data.user.username)
        session.set('_useremail', response.data.user.useremail)
        session.set('_userlevel', response.data.user.userlevel)
        session.set('_userlevelname', response.data.user.userlevelname)

        navigateTo(`${store.state.urlBase}`)

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
}
</script>

<style scoped lang="scss">

@use "sass:color";
@use "@scss/variables" as *;

.logo-container{
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: #eee;
}
.logo-container .logo-header{
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 20px;
    padding: 15px;
    width: 300px;
}
.logo-container .logo-header img{
    width: 100px;
}
.logo-container .logo-body form{
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
.logo-container .logo-body form span{
    margin-bottom: 20px;
    color: color.adjust($primary, $lightness: -5%);
}
.logo-container .logo-body form .middle{
    margin-bottom: 20px;
    width: 300px;
}
.logo-container .logo-body form .middle div{
    display: flex;
    flex-direction: column;
}
.logo-container .logo-body form .middle div label{
    color: color.adjust($tertiary, $lightness: -20%);
    font-size: 11pt;
    font-weight: bold;
    margin-bottom: 5px;
}
.logo-container .logo-body form .middle div input{
    color: $secondary;
    margin-bottom: 5px;
    padding: 10px;
    border: solid 1px $tertiary;
    border-radius: 3px;
    font-size: 12pt;
    background-color: #fff;
}
.logo-container .logo-body form button{
    background-color: $primary;
    padding: 10px 15px;
    color: #ffffff;
    border: solid 1px color.adjust($primary, $lightness: 15%);
    border-radius: 3px;
    cursor: pointer;
    width: 100%;
}

</style>
