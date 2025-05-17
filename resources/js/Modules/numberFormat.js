export default {

    run(num, decimals = 2){

        let number = (Math.round(num * 100) / 100).toFixed(decimals)

        return number.replace('.', ',')
    },
}
