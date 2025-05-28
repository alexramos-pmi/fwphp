import { createStore } from "vuex";

export default createStore({
    state:{
        urlBase: 'http://localhost/fwphp/public',
        //urlBase: 'http://localhost:8000',
        modal: false,
        modalDel: false,
        modalPdf: false,
        drawer: false,
        alternativas: [
            {id: 1, name: 'Sim'},
            {id: 2, name: 'Não'}
        ],
        levels: [
            {id: 1, name: 'Operador'},
            {id: 2, name: 'Admin'}
        ],
        auth: {
            admin: [2],
            operador: [1,2]
        },
        updatePage: false,
        login: {
            email: 'alexramos.pmi@gmail.com',
            password: '123'
        },
        loader: false,
        loading: false,
        snackbar: '',
        model: {},
        user: {
            id: 0,
            name: '',
            email: '',
            password: '',
            level: '',
            foto: null
        },
        messages: {
            success: '',
            errors: '',
            errorsFull: []
        },
        snackbar: {
            type: '',
            message: '',
            status: false
        },
        users: [],
        menus: []
    },
    getters: {},
    mutations: {

        updateStateProperty(state, { objectName, key = null, value, mode = 'set', uniqueKey = 'id' }){

            // ⚠️ Correção: Verificar se o objeto existe (aceitando booleanos, números, strings)
            if(state[objectName] === undefined){

                console.warn(`Objeto '${objectName}' não encontrado no state.`)

                return;
            }

            // ✅ Caso o próprio objeto seja um array
            if(Array.isArray(state[objectName])){

                if(mode === 'replace') {

                    state[objectName] = value

                }
                else if(mode === 'add'){

                    const itemExists = state[objectName].some(item => item[uniqueKey] === value[uniqueKey])

                    if(!itemExists){

                        state[objectName].push(value)
                    }
                    else{

                        console.warn(`Item com ${uniqueKey} '${value[uniqueKey]}' já existe em '${objectName}'`)
                    }
                }
                else if(mode === 'remove'){

                    state[objectName] = state[objectName].filter(item => item[uniqueKey] !== value[uniqueKey])
                }
                else{

                    console.warn(`Modo '${mode}' não reconhecido para arrays.`)
                }
                return;
            }

            // ✅ Se o modo for 'replace', substituímos o objeto inteiro
            if(mode === 'replace'){

                state[objectName] = value

                return
            }

            // ✅ Se não houver key, atribuímos diretamente
            if(!key){

                // 🚀 **Correção: Se `value` for primitivo, atribuir diretamente**
                if (typeof value !== 'object' || value === null){

                    state[objectName] = value
                }
                else{

                    state[objectName] = { ...state[objectName], ...value }
                }

                return;
            }

            // Se a chave não existir no objeto, avisamos e saímos
            if(!Object.prototype.hasOwnProperty.call(state[objectName], key)){

                console.warn(`Propriedade '${key}' não encontrada no objeto '${objectName}'`)

                return;
            }

            let property = state[objectName][key];

            // ✅ Se for um array dentro de um objeto
            if(Array.isArray(property)){

                if(mode === 'add'){

                    const itemExists = property.some(item => item[uniqueKey] === value[uniqueKey])

                    if(!itemExists){

                        property.push(value)
                    }
                    else{

                        console.warn(`Item com ${uniqueKey} '${value[uniqueKey]}' já existe em '${objectName}.${key}'`)
                    }
                }
                else if(mode === 'remove'){

                state[objectName][key] = property.filter(item => item[uniqueKey] !== value[uniqueKey])

                }
                else{

                    console.warn(`Modo '${mode}' não reconhecido para arrays.`);
                }
            }
            else{

                // ✅ Atualiza apenas a propriedade desejada (modo 'set')
                state[objectName][key] = value;
            }
        }
    },
    actions:{},
    modules:{}
})
