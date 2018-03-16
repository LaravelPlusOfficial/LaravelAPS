<template>
    <div v-if="show">
        <div ref="captchaZone"></div>
        <input type="hidden" name="gRecaptchaResponse" v-model="response" v-if="input">
    </div>
</template>

<script>

    export default {
        name: "recaptcha",

        props: {
            dataShow: {
                type: Boolean,
                default: false
            },
            dataReset: {
                type: Boolean,
                default: false
            },
            input: {
                type: Boolean,
                default: false
            }
        },

        data() {
            return {
                show: true,
                captchaId: null,
                response: null,
                options: {
                    'sitekey': App.recaptcha.sitekey,
                    'theme': App.recaptcha.theme,
                    'callback': this.verifyCallback,
                }
            }
        },

        mounted() {

            if( window.grecaptcha ) {
                this.render()
            }

            Event.listen('recaptcha-loaded', this.render)
        },

        methods: {

            render() {
                this.captchaId = window.grecaptcha.render(this.$refs.captchaZone, this.options)
            },

            verifyCallback(response) {
                this.response = response

                this.$emit('verified', {
                    response: response,
                    captchaId: this.captchaId
                })
            },
        },

        watch: {

            show: function (newVal, oldVal) {
                if (newVal && window.hasOwnProperty('grecaptcha')) {
                    this.render()
                }
            },

            dataShow: function (val) {
                if (!val) {
                    this.show = false
                }
            },

            dataReset: function (newVal, oldVal) {
                if (newVal) {
                    window.grecaptcha.reset(this.captchaId)
                }
            }


        }
    }
</script>