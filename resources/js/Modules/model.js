import axios from 'axios'

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')

if(token){
  axios.defaults.headers.common['X-CSRF-TOKEN'] = token
}
else{
  console.warn('CSRF token não encontrado no <meta>')
}

axios.defaults.baseURL = 'http://localhost/syscong/public'
//axios.defaults.baseURL = 'http://localhost:8000'
axios.defaults.headers.common['accept'] = 'application/json'
axios.defaults.withCredentials = true;

export default {

    apiGet(uri, id = false)
    {
        if(id)
        {
            uri = uri + '/' + id
        }

        return axios.get(uri).then((response) => {

            return response

        })
    },

    apiPost(uri, data){

        return axios.post(uri, data).then((response) => {

            return response

        })
    },

    apiPut(uri, id, data){

        data.append('_method', 'PUT')

        uri = uri + '/' + id

        return axios.post(uri, data).then((response) => {

            return response

        })
    },

    apiDel(uri, id){

        uri = uri + '/' + id

        console.log(uri);

        return axios.delete(uri, {_method: 'DELETE'}).then((response) => {

            return response

        })
    }
}
