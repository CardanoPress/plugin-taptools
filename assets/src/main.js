window.addEventListener('alpine:init', () => {
    const Alpine = window.Alpine || {}
    const cardanoPress = window.cardanoPress || {}

    Alpine.data('cardanoPressTapTools', () => ({
        isProcessing: false,
        transactionHash: '',

        async init() {
            console.log('CardanoPress TapTools ready!')
        },
    }))
})
