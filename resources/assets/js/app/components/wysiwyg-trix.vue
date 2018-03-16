<template>
    <div>
        <input :id="id" type="hidden" :name="name" :value="value">

        <trix-editor
                ref="trix"
                :input="id"
                @trix-change="change"
                autofocus
                :placeholder="placeholder">
        </trix-editor>
    </div>
</template>

<script>
    export default {
        name: "wysiwyg-trix",

        props: ["id", "name", "value", "placeholder"],

        data() {
            return {
                editor: null
            }
        },

        methods: {
            change({target}) {
                this.$emit('input', target.value)
            }
        },

        // destroye() {
        //
        //     this.$refs.trix.value = ""
        //
        // },

        watch: {
            value(val) {
                if( val === "" ) {
                    this.$refs.trix.value = ""
                }
            }
        }

    }
</script>