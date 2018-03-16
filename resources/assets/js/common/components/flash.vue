<template>
    <slide-y-up-transition>
        <div class="flash p-fx t-0 l-0 r-0 d-f ai-c" :class="classes" v-show="show">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="d-f ai-c jc-c fill-white c-white">
                            <vue-svg :name="icon" square="20"></vue-svg>
                            <p class="mb-0 ml-3" v-text="message">Message</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </slide-y-up-transition>
</template>

<script>
    import {SlideYUpTransition} from 'vue2-transitions'

    export default {
        props: ['dataLevel', 'dataMessage'],

        components: {
            SlideYUpTransition
        },

        data() {
            return {
                message: this.dataMessage,
                level: this.dataLevel,
                show: false
            }
        },

        created() {
            if (this.message) {
                this.flash();
            }

            window.Event.listen('flash', data => this.flash(data));
        },

        methods: {
            flash(data) {
                if (data) {
                    this.message = data.message;
                    this.level = data.level;
                }

                this.show = true;

                this.hide();
            },

            hide() {
                setTimeout(() => this.show = false, 3000);
            }
        },

        computed: {
            classes() {
                return {
                    'grad-error': this.level === 'error',
                    'grad-atlas': this.level === 'info',
                    'grad-success': this.level === 'success',
                }
            },

            icon() {

                switch (this.level) {

                    case 'error':
                        return 'icon-x-circle'
                        break;

                    case 'info':
                        return 'icon-info'
                        break;

                    default:
                        return 'icon-check'
                }

            }
        }
    };
</script>