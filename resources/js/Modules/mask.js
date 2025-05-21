export default {

    values(element){

        let value = element.target.value
        value = value.replace(/\D/g, "") //Remove tudo que não for número
        value = value.replace(/(\d+)(\d{2})$/, "$1,$2")//Acrescenta 2 dígitos após a vígula
        value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")//Acrescenta ponto de milhar

        return value
    },

    nota(val){

        // Remove tudo que não é número
        let valor = val.replace(/\D/g, "")

        // Limita o valor a 3 dígitos no total
        if(valor.length > 3){

            valor = valor.slice(0, 3)
        }

        // Determina as partes inteira e decimal
        let parteInteira, parteDecimal;

        if(valor.length === 3){

            // Se houver três dígitos, a parte inteira tem 2 e a decimal 1
            parteInteira = valor.slice(0, 2);
            parteDecimal = valor.slice(2, 3);
        }
        else{

            // Caso contrário, o primeiro dígito é a parte inteira
            parteInteira = valor.slice(0, 1);
            parteDecimal = valor.slice(1, 3);
        }

        // Combina as partes com a vírgula
        if(parteDecimal){

            return `${parteInteira},${parteDecimal}`
        }

        return parteInteira
    },

    phone(element){

        let value = element.target.value

        value = value.replace(/\D/g, "")
        value = value.replace(/^(\d{2})(\d)/g, "($1) $2")
        value = value.replace(/(\d)(\d{4})$/, "$1-$2")

        return value
    },

    date(element){

        let value = element.target.value

        // Remove tudo o que não é dígito
        value = value.replace(/\D/g, '');

        // Adiciona a máscara dd/mm/aaaa
        if (value.length > 2) {
            value = value.slice(0, 2) + '/' + value.slice(2);
        }

        if (value.length > 5) {
            value = value.slice(0, 5) + '/' + value.slice(5, 9);
        }

        return value
    }
}
