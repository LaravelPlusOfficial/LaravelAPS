<template>
    <div :style="{ 'max-width' : maxWidth }">

        <div>
            <fade-transition>
                <img :src="file" v-show="file" class="mw-100 mb-3">
            </fade-transition>

            <input type="file"
                   :name="name"
                   style="display: none"
                   ref="fileField"
                   v-if="!deleted"
                   @change="processFile($event)">

            <input type="text" :name="name" style="display: none;" value="" v-if="deleted">

        </div>

        <div class="d-f jc-c">
            <button class="bg-n p-0 m-0 lh-1 bd-n mr-2 fill-gray fill-gray-dark-hv cur-p o-n-fc"
                    type="button"
                    v-show="! file"
                    @click.prevent="openSelector()">
                <vue-svg name="icon-image" square="20"></vue-svg>
            </button>
            <button class="bg-n p-0 m-0 lh-1 bd-n mr-2 fill-gray fill-gray-dark-hv cur-p o-n-fc"
                    type="button"
                    v-show="file"
                    @click.prevent="openSelector()">
                <vue-svg name="icon-edit" square="20"></vue-svg>
            </button>
            <button class="bg-n p-0 m-0 lh-1 bd-n fill-gray fill-gray-dark-hv cur-p o-n-fc"
                    type="button"
                    v-show="file"
                    @click.prevent="removeImage()">
                <vue-svg name="icon-delete" square="20"></vue-svg>
            </button>

            <slot name="buttons" :file="file"></slot>

        </div>

    </div>
</template>

<script>
    export default {
        name: "file-selector",

        props: ['src', 'maxWidth', 'name', 'deleteInput'],

        data() {
            return {
                file: null,
                deleted: false
            }
        },

        mounted() {
            this.file = this.src ? this.src : null
        },

        methods: {

            openSelector() {
                this.deleted = false

                this.$refs.fileField.click()
            },

            processFile(e) {
                let files = e.target.files

                this.$emit('input', files)

                this.preview(files[0])
            },

            preview(file) {
                let reader = new FileReader();

                reader.onload = event => {
                    this.file = event.target.result
                };

                reader.readAsDataURL(file)
            },

            removeImage() {
                this.file = null
                this.$refs.fileField.value = ''
                this.deleted = true
            }

        }
    }
</script>