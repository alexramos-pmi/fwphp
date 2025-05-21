import Store from "@/Store"

export default {

    user(){

        Store.commit('updateStateProperty', {
            objectName: 'modal',
            value: false
        })

        Store.commit('updateStateProperty', {
            objectName: 'modalDel',
            value: false
        })

        Store.commit('updateStateProperty', {
            objectName: 'messages',
            value: {
                success: '',
                error: '' ,
                errors: []
            }
        })

        Store.commit('updateStateProperty', {
            objectName: 'user',
            value: {
                id: 0,
                name: '' ,
                email: '',
                password: '',
                level: '',
                setor: ''
            }
        });
    },
}
