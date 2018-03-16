<template>
    <div>
        <div class="toggle mt-4 ta-l fxg-1 w-100 mw-90">
            <label for="googleDomainPlus" class="fsz-sm ls-11 tt-u">
                <input type="checkbox"
                       name="googleDomainPlus"
                       id="googleDomainPlus"
                       :checked="providersStatus.googleDomainPlus"
                       @click="toggleProvider('googleDomainPlus', $event.target.checked)">
                <span>Google domain plus</span>
            </label>
        </div>

        <div class="toggle mt-4 ta-l fxg-1 w-100 mw-90">
            <label for="facebook" class="fsz-sm ls-11 tt-u">
                <input type="checkbox"
                       name="facebook"
                       id="facebook"
                       @click="toggleProvider('facebook', $event.target.checked)">
                <span>Facebook</span>
            </label>
        </div>

        <div class="toggle mt-4 ta-l fxg-1 w-100 mw-90">
            <label for="twitter" class="fsz-sm ls-11 tt-u">
                <input type="checkbox"
                       name="twitter"
                       id="twitter"
                       @click="toggleProvider('twitter', $event.target.checked)">
                <span>Twitter</span>
            </label>
        </div>

    </div>
</template>

<script>
    export default {
        name: "social-auto-post",

        props: ['toProviderUrl', 'providersStatus'],

        data() {
            return {
                googleDomainPlusToProvider: this.toProviderUrl.replace('%provider%', 'googleDomainPlus'),
                facebookToProvider: this.toProviderUrl.replace('%provider%', 'facebook'),
                twitterToProvider: this.toProviderUrl.replace('%provider%', 'twitter'),
            }
        },

        methods: {

            toggleProvider(name, checked = false) {

                if (checked) {
                    this.$confirm("Are you sure you want to enable auto posting for Google Domain Plus")
                        .then(() => window.location.href = this.googleDomainPlusToProvider)
                } else {
                    this.$confirm("Are you sure you want to disable auto posting for Google Domain Plus")
                        .then(() => window.location.href = this.googleDomainPlusToProvider.replace('/enable/', '/disable/'))
                }

            }


        }


    }
</script>