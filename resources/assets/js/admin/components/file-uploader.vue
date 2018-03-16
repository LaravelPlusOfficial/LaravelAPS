<template>
    <div>
        <div id="dropzone-media-upload"
             class="vue-dropzone"
             ref="dropzoneElement">
            <div class="dz-default dz-message d-f ac-c ai-c jc-c">
                <vue-svg name="icon-upload" square="20" classes="fill-gray mr-2"></vue-svg>
                <p class="mb-0 fsz-sm tt-u ls-12 c-gray">Browse</p>
            </div>
        </div>
    </div>
</template>

<script>
    import Dropzone from 'dropzone'

    export default {
        name: "file-uploader",

        props: {
            uploadUrl: {
                type: String,
                required: true
            },
            reset: {
                type: Boolean,
                default: false
            }
        },

        data() {
            return {
                show: false,
                dropzone: null,
                validMimeTypes: [".png", ".jpg", ".jpeg", ".gif"],
                files: [],
            }
        },

        mounted() {
            this.init()
            this.$watch('reset', () => console.log("hello"));
        },

        methods: {
            init() {
                Dropzone.autoDiscover = false

                this.dropzone = new Dropzone(this.$refs.dropzoneElement, this.dropzoneSettings)

                this.dropzone.on('success', (file, response) => {
                    console.log(response)
                    this.files.push(response)
                })

                this.dropzone.on('complete', (file, response) => {
                    console.log(file)
                    console.log(response)
                })

                this.dropzone.on('queuecomplete', () => {
                    let files = this.files

                    this.files = []

                    this.$emit('upload-complete', files)
                })
            }
        },

        computed: {

            dropzoneSettings() {
                return {
                    url: this.uploadUrl,
                    paramName: "file",
                    maxFilesize: 5, //MB
                    headers: {
                        'X-CSRF-TOKEN': App.csrfToken
                    },
                    acceptedFiles: this.validMimeTypes.join(','),
                }
            },
        },

        beforeDestroy() {
            this.files = []
        }
    }
</script>