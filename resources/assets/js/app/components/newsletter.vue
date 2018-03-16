<template>

    <section class="newsletter newsletter-bg grad-atlas pt-5 pb-5">

        <div class="container-fluid mt-5 mb-5">

            <div class="row">

                <div class="col-lg-12">

                    <transition name="slide-fade" mode="out-in">

                        <div class="" v-if="! done" key="input">
                            <h4 class="h5 ta-c c-white tt-u pb-2 mb-2">
                                <span class="fw-200">Sign up for</span>
                                <span class="ls-14">newsletter</span>
                            </h4>

                            <div class="d-f jc-c w-100">
                                <form @submit.stop.prevent class="d-f jc-c ac-c w-100 p-rel" style="max-width: 500px">

                                    <input type="email"
                                           placeholder="Enter your email..."
                                           class="input-transparent w-100 ls-12"
                                           v-model="email"
                                           @keyup.enter.stop.prevent="subscribe()"
                                           style="padding-right: 65px">

                                    <button class="btn btn-link p-ab d-f ac-c ai-c"
                                            type="button"
                                            @click="subscribe()"
                                            style="top: 14px; right: 16px; padding:0">
                                        <loader :loading="subscribing" color="white" height="26px"></loader>
                                        <vue-svg v-if="! subscribing"
                                                 name="icon-send"
                                                 :height="30"
                                                 :width="30"
                                                 classes="fill-white"
                                        ></vue-svg>
                                    </button>

                                </form>
                            </div>

                            <p class="ta-c c-black mb-0" v-text="errorMessage" v-show="errorMessage"></p>
                        </div>

                        <div class="d-f jc-c ai-c w-100" style="min-height: 102px;" v-else key="result">
                            <p class="d-f ta-c c-white tt-u ls-12 m-0" v-text="doneMessage"></p>
                            <button class="btn btn-link d-f p-0 ml-2" @click="reset()">
                                <vue-svg name="icon-reload"
                                         :height="18"
                                         :width="18"
                                         classes="fill-white"
                                ></vue-svg>
                            </button>
                        </div>

                    </transition>

                </div>

            </div>

        </div>

    </section>

</template>

<script>
    import axios from 'axios'

    export default {
        name: "newsletter",

        props: ['subscribeUrl'],

        data() {
          return {
              subscribing: false,
              email: null,
              done: false,
              doneMessage: '',
              errorMessage: null
          }
        },

        methods: {
            subscribe() {
                this.errorMessage = null

                this.subscribing = true

                axios.post(this.subscribeUrl, { email: this.email})
                    .then(response => {
                        this.doneMessage = 'Thank you for newsletter subscription'

                        this.reset()

                        this.done = true
                    })
                    .catch(error => {
                        this.subscribing = false

                        this.reset()

                        this.errorMessage = "Email is not valid, or its already been subscribed"
                    })

            },

            reset() {
                this.done = false

                this.email = ''

                this.subscribing = false

                this.errorMessage = null
            }
        }
    }
</script>

<style lang="scss">

    .slide-fade-enter-active {
        transition: all .3s ease;
    }
    .slide-fade-leave-active {
        transition: all .2s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }
    .slide-fade-enter, .slide-fade-leave-to
        /* .slide-fade-leave-active below version 2.1.8 */ {
        transform: translateX(10px);
        opacity: 0;
    }

</style>