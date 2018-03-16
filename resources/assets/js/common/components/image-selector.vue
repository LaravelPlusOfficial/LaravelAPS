<template>
    <div>

        <fade-transition>
            <slot name="image" :image="image"></slot>
        </fade-transition>

        <input type="file"
               name="name"
               ref="field"
               style="display: none"
               ref="selector">

        <slot name="controls" :change="change" :remove="remove"></slot>

    </div>
</template>

<script>
    export default {
        name: "image-selector",

        props: ['name', 'src'],

        data() {
            return {
                image: this.src
            }
        },

        methods: {
            change() {
                this.$refs.selector.click()
            },

            processFile(e) {
                let files = e.target.files

                let reader = new FileReader();

                reader.onload = event => {
                    this.image = event.target.result
                };

                reader.readAsDataURL(files[0])
            },

            remove() {
                this.image = null
                this.$refs.selector.value = ''

                this.$nextTick(() => {
                    this.$refs.fieldInput.checked = true
                })
            }
        }
    }
</script>