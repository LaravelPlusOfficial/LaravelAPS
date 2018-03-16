require('./bootstrap')

require('../common/VueFilters')

import VueScrollTo from 'vue-scrollto'
Vue.use(VueScrollTo)

import Directives from '../common/directives/index'
Vue.use(Directives)

import VueMatchHeights from 'vue-match-heights';
Vue.use(VueMatchHeights);

Vue.component('social-share', require('../common/components/social-share'))

Vue.component('wysiwyg', require('./components/wysiwyg-trix'))

Vue.component('recaptcha', require('../common/components/recaptcha'))

Vue.component('flash', require('../common/components/flash'))

Vue.component('loader', require('../common/components/loader'))

Vue.component('vue-svg', require('../common/components/vue-svg'))

Vue.component('newsletter', require('./components/newsletter'))

// Comment System
Vue.component('comment-system', require('./components/comment-system/comment-system'))
Vue.component('comments', require('./components/comment-system/comments'))
Vue.component('comment', require('./components/comment-system/comment'))
Vue.component('comment-reply', require('./components/comment-system/comment-reply'))

const vueApp = new Vue({
    el: '#app',

    data: {
        search: false
    },

    methods: {

        toggleOffCanvas() {
            let opened = this.$refs.offCanvasMenu.classList.toggle('open')
            if (opened) {
                document.body.classList.add('off-canvas-open-left', 'ovf-h')
            } else {
                document.body.classList.remove('off-canvas-open-left', 'ovf-h')
            }

        },
    }

})

window.recaptchaLoaded = () => {
    Event.fire('recaptcha-loaded')
}