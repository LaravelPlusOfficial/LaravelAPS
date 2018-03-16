<template>
    <div class="" v-if="url">
        <a href="#"
           @click.prevent="share('facebook')"
           target="_blank">
            <vue-svg name="icon-facebook-colored" square="16"></vue-svg>
        </a>
        <a href="#"
           @click.prevent="share('tweet')"
           target="_blank" class="mr-2 ml-2">
            <vue-svg name="icon-twitter-colored" square="16"></vue-svg>
        </a>
        <a href="#"
           @click.prevent="share('googlePlus')"
           target="_blank">
            <vue-svg name="icon-google-plus-colored" square="16"></vue-svg>
        </a>
    </div>
</template>

<script>
    export default {
        name: "social-share",

        props: ['url', 'title', 'description', 'twitterUsername', 'hashtags'],

        data() {
            return {
                popup: {
                    width: 557,
                    height: 453
                },
                fbAppId: window.App.fb_app_id
            }
        },

        computed: {

            facebook() {
                let path = `https://www.facebook.com/dialog/share?`

                return path + `app_id=${this.fbAppId}&display=popup&href=${this.url}`
            },

            tweet() {
                let path = `https://twitter.com/share?${this.url}`

                if (this.twitterUsername) {
                    let via = this.twitterUsername.replace('@', '')
                    path = path + `&via=${via}`
                }

                if (this.title) {
                    path = path + `&text=${this.title}`
                }

                if (this.hashtags) {
                    path = path + `&hashtags=${this.hashtags}`
                }

                return path
            },

            googlePlus() {
                return `https://plus.google.com/share?url=${this.url}`
            }

        },

        methods: {
            share(type) {

                let vP = Math.floor((window.innerWidth - this.popup.width) / 2)
                let hP = Math.floor((window.innerHeight - this.popup.height) / 2)

                let x = `width=${this.popup.width},height=${this.popup.height},left=${vP},top=${hP}`
                x = x + ',location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1'

                let popup = window.open(this[type], 'Social Share', this.getSpecs(vP, hP));

                if (popup) {
                    popup.focus();
                    return false;
                }
            },

            getSpecs(vP, hP) {

                let spec = `width=${this.popup.width},height=${this.popup.height},left=${vP},top=${hP}`

                return spec + ',location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1'
            }
        }
    }
</script>