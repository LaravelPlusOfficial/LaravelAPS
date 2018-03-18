require('./bootstrap')

require('../common/VueFilters')

require('../common/VuePrototypes')

require('../common/VueMixins')

import Directives from '../common/directives/index'

Vue.use(Directives)

import VModal from 'vue-js-modal'

Vue.use(VModal, {dialog: true})

Vue.component('flash', require('../common/components/flash'))

Vue.component('loader', require('../common/components/loader'))

Vue.component('vue-svg', require('../common/components/vue-svg'))

Vue.component('taxonomies', require('./components/taxonomies'))

Vue.component('taxonomies-list', require('./components/taxonomies-list'))

Vue.component('taxonomy-item', require('./components/taxonomy-item'))

Vue.component('queue-monitoring', require('./components/queue-monitoring'))

Vue.component('manage-post', require('./components/manage-post'))

Vue.component('tag-input', require('./components/tag-input'))

Vue.component('file-selector', require('./components/file-selector'))

Vue.component('file-uploader', require('./components/file-uploader'))

Vue.component('media-library', require('./components/media-library'))

// Vue.component('wysiwyg', require('./components/wysiwyg-admin-trix'))

Vue.component('wysiwyg', require('./components/wysiwyg-medium'))

Vue.component('vue-confirm', require('./components/vue-confirm'))

Vue.component('avatar', require('./components/avatar'))

//Vue.component('social-auto-posting', require('./components/social-auto-posting'))


const admin = new Vue({
    el: '#admin',

    methods: {
        toggleOffCanvas() {
            this.$refs.offCanvasMenu.classList.toggle('open')
        },

        confirmAndSubmit(msg) {
            let form = event.target
            this.$confirm(msg).then(() => form.submit())
        },

        toggleSocialAutoPostSetting(groupName, settingKey, value, enableUrl, disableUrl) {
            let providerName = settingKey.replace(`${groupName}_`, '');

            if (value === 'enable') {

                this.$confirm(`Are you sure to enable ${providerName} auto posting`)
                    .then(() => window.location.href = enableUrl.replace('%provider%', providerName))

            } else {

                this.$confirm(`Are you sure to disable ${providerName} auto posting`)
                    .then(() => window.location.href = disableUrl.replace('%provider%', providerName))

            }
        }
    }

})

// Bind confirm to vue instance
window.confirm = (message) => {
    return admin.$confirm(message)
}

window.scriptLoaded = (scriptName) => {
    window.Event.fire(scriptName);
}