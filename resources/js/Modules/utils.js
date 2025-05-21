import Store from "../Store"

export default {

    chart(config){

        (async function() {

            new Chart(
                document.getElementById(config.element),
                {
                    type: config.type,
                    data: {
                        labels: config.data.labels,
                        datasets: [
                            {
                                label: config.data.datasets[0].label,
                                data: config.data.datasets[0].data,
                                backgroundColor: config.data.datasets[0].backgroundColor,
                                borderWidth: config.data.datasets[0].borderWidth
                            }
                        ]
                    },
                    options: {
                        indexAxis: config.options.indexAxis
                    }
                }
            );
        })();
    },
    showErrors(error){

        Store.state.redirectRoute = ''

        let title = ''
        let message = ''

        if(error.errorsFull){

            Store.commit('setMessages', {
                success: '',
                errors: '',
                errorsFull: error.errorsFull
            })

            title = 'Atenção!'
            message = 'Existem errors no formulário, corrija-os'
        }
        else if(error.errors)
        {
            Store.commit('setMessages', {
                success: '',
                errors: error.errors,
                errorsFull: ''
            })

            title = 'Atenção!'
            message = error.errors
        }

        Store.state.textoMessage.title = title
        Store.state.textoMessage.content = message
        Store.state.modalMessages = true
    },
    formatMoney(value) {

        if (!value) return "";

        // Remove tudo que não for número
        value = value.toString().replace(/\D/g, "");

        // Divide por 100 para manter duas casas decimais
        value = (parseFloat(value) / 100).toFixed(2);

        // Converte para o formato brasileiro (1.234,56)
        return new Intl.NumberFormat("pt-BR", {

            minimumFractionDigits: 2,
            maximumFractionDigits: 2,

        }).format(value);
    }
}
