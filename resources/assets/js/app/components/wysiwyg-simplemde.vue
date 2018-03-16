<template>
    <div>
        <textarea :ref="id"></textarea>
    </div>
</template>

<script>
    export default {

        props: {
            id: {
                type: String,
                required: true
            },
            inputId: {
                type: String,
                default: ''
            },
            placeholder: {},
            reset: {
                type: Boolean,
                default: false
            },
            value: {}
        },

        data() {
            return {
                editor: null
            }
        },

        mounted() {

            this.init()

            this.$watch('reset', (newVal, oldVal) => newVal ? this.resetEditor() : '' )

        },

        updated() {
            this.editor.value( this.value ? this.value : '' )
        },

        methods: {
            init: function () {
                this.editor = new SimpleMDE({
                    element: this.$refs[this.id],
                    showIcons: ["code"],
                    hideIcons: ["guide", "image", "table", "heading"],
                    indentWithTabs: true,
                    lineWrapping: true,
                    placeholder: this.placeholder,
                    renderingConfig: {
                        singleLineBreaks: false
                    }
                })

                if(this.value) {
                    this.editor.value(this.value)
                }

                this.editor.codemirror.on("change", () => {
                    this.$emit('input', this.editor.value())
                });
            },

            resetEditor: function () {
                this.editor.value("")
            }
        }

    }
</script>