<script>
    export default {
        name: "media-library",

        props: {
            mediaUrl: {
                type: String,
                required: true
            },
            embed: {
                type: Boolean,
                default: true
            }
        },

        data() {
            return {
                mediaItems: [],
                uploading: false,
                editing: null,
                paginate: {},
                media: {},
                show: this.embed,
                selection: {
                    type: null,
                    multiple: false,
                    callback: null
                }
            }
        },

        mounted() {
            this.fetchMedia()

            this.$watch('editing', val => !val ? this.media = {} : '')

            if( ! this.embed ) {
                document.addEventListener("keydown", (e) => {
                    if (this.show && e.keyCode == 27) {
                        this.show = false
                        this.reset()
                    }
                });
            }

            Event.listen('select-featured-image', (data) => this.reactToEvent(data))

            Event.listen('select-media-for-wysiwyg', (data) => this.reactToEvent(data))
        },

        methods: {

            fetchMedia() {
                axios.get(this.mediaUrl)
                    .then(data => {
                        this.mediaItems = data.data.data

                        if (this.mediaItems.length <= 0) {
                            this.uploading = true
                        }

                        delete(data.data.data)

                        this.paginate = Object.assign({}, data.data)
                    })
            },

            deleteMedia(id) {
                this.$confirm("Are you sure you want to delete this media").then(() => {
                    axios.delete(this.mediaUrl + '/' + id, {data: {id: id}})
                        .then(res => {
                            this.reset()
                            this.fetchMedia()
                            flash('Media deleted')
                        })
                })

            },

            reset() {
                this.uploading = false
                this.editing = false
                this.selection.type = null
                this.selection.multiple = false
                this.selection.callback = null
            },

            filesUploaded(files) {
                this.uploading = false
                this.fetchMedia()
            },

            sizeOnDisk(media) {
                let total = 0

                Object.keys(media.variations).forEach(v => {
                    total = media.variations[v].size ? total + media.variations[v].size : total
                })

                return total
            },

            hide() {
                if (!this.embed) {
                    this.show = false
                    this.reset()
                }
            },

            select(item) {
                this.selection.callback(item)

                this.hide()
            },

            reactToEvent(data) {
                this.show = true
                this.selection.type = data.type
                this.selection.multiple = data.multiple
                this.selection.callback = data.callback
            }

        }

    }
</script>