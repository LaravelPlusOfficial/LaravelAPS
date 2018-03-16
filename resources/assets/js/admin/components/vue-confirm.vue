<template>
    <fade-transition>
        <div class="confirm-wrap d-f jc-c ac-c ai-c" v-if="show" @keydown.esc="hide()">
            <div class="confirm-box bdr-5">

                <div class="confirm-msg p-4 bdB" v-html="message"></div>

                <div class="confirm-btns d-f jc-sb pl-4 pr-4 pt-3 pb-3">

                    <button type="button"
                            @click="notAccepted()"
                            class="btn btn-secondary fsz-sm tt-u ls-12">Cancel</button>

                    <button type="button"
                            @click="accepted()"
                            class="btn btn-primary fsz-sm tt-u ls-12">Confirm</button>
                </div>
            </div>
        </div>
    </fade-transition>
</template>

<script>
    import {FadeTransition} from 'vue2-transitions'

    export default {
        name: "vue-confirm",

        components: {
            FadeTransition
        },

        data() {
            return {
                message: null,
                show: null,
                data: {}
            }
        },

        mounted() {
            Event.listen('confirm', this.showWindow)
            
            this.$watch('show', val => ! val ? this.data = {} : '' )

            document.addEventListener("keydown", (e) => {
                if (this.show && e.keyCode == 27) {
                    this.show = false
                }
            });
        },

        methods: {

            showWindow(data) {
                this.message = data.message
                this.data.resolve = data.resolve
                this.data.reject = data.reject
                this.show = true
            },

            accepted() {
                this.data.resolve('accepted')
                this.show = false
            },

            notAccepted() {
                this.data.reject('not accepted')
                this.show = false
            }

        }
    }
</script>

<style lang="scss">

    .confirm-wrap {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1080;
    }

    .confirm-box {
        width: 500px;
        max-width: 90%;
        background: white;
    }

</style>