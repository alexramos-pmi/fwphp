// src/plugins/router.js
import axios from 'axios'
import { ref } from 'vue'

const state = ref({
  visiting: false,
  errors: {},
})

const request = async (method, url, data = {}, options = {}) => {
  state.value.visiting = true
  state.value.errors = {}

  try {
    const response = await axios({
      method,
      url,
      data,
      ...options,
    })

    if (response.data?.redirect) {
      window.location.href = response.data.redirect
    }

    return response
  } catch (error) {
    if (error.response?.status === 422) {
      state.value.errors = error.response.data.errors || {}
    }

    throw error
  } finally {
    state.value.visiting = false
  }
}

const router = {
  get: (url, options = {}) => request('get', url, {}, options),
  post: (url, data, options = {}) => request('post', url, data, options),
  put: (url, data, options = {}) => request('put', url, data, options),
  patch: (url, data, options = {}) => request('patch', url, data, options),
  delete: (url, data = {}, options = {}) => request('delete', url, data, options),
  state,
}

export default {
  install: (app) => {
    app.config.globalProperties.$router = router
  },
}