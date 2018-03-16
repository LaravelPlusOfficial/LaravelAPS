<script>
    import {FadeTransition} from 'vue2-transitions'
    // https://github.com/serversideup/roastandbrew/blob/master/resources/assets/js/components/global/forms/TagsInput.vue

    export default {

        props: {
            tagUrl: {
                type: String,
                required: true
            },
            tagsData: {
                type: Array,
                default: () => []
            }
        },

        components: {
            FadeTransition
        },

        data() {
            return {
                tag: '',
                tags: this.tagsData,
                suggestions: [],
                focusedIndex: -1,
                tagAddedAlready: false,
                addingToDatabase: false
            }
        },

        mounted() {
            if( this.tagsData.length > 0 ) {
                // this.tagsData.push(tag => this.tags.push(tag))
            }
        },

        watch: {
            tagAddedAlready(val, old) {
                if (val) {
                    setTimeout(() => this.tagAddedAlready = false, 3000)
                }
            },
        },

        methods: {

            searchTags() {

                if (this.tag.length > 2) {
                    axios.get(this.tagUrl, {params: {search: this.tag}})
                        .then(({data}) => {
                            this.suggestions = data.filter(x => !this.tags.filter(t => t.slug === x.slug).length > 0)
                        })
                        .catch(error => console.log(error))
                }

            },

            focusSuggestion(dir = 'down') {

                if (dir === 'down') {
                    this.focusedIndex = this.focusedIndex === (this.suggestions.length - 1) ? 0 : ++this.focusedIndex
                } else {
                    this.focusedIndex = this.focusedIndex === -1 ? (this.suggestions.length - 1) : --this.focusedIndex
                }

            },

            addTag(tag = null) {
                
                this.addingToDatabase = false
                let tagAdded = false

                if (tag) {

                    !this.alreadyTagged(tag) ? this.tags.push(tag) : this.tagAddedAlready = true

                } else if (this.focusedIndex > -1) {

                    tag = this.suggestions[this.focusedIndex]

                    !this.alreadyTagged(tag) ? this.tags.push(tag) : this.tagAddedAlready = true

                } else if (this.tagIsInSuggestion()) {

                    tag = this.suggestions.filter(t => t.name === this.tag)[0]

                    !this.alreadyTagged(tag) ? this.tags.push(tag) : this.tagAddedAlready = true

                } else {

                    this.tag.length > 0 ? this.addTagToDatabase() : ''

                }

                tag ? this.$emit('tag-selected', tag) : ''

                this.reset()
            },

            addTagToDatabase() {
                this.addingToDatabase = true

                let name = this.tag

                axios.post(this.tagUrl, {name: name})
                    .then(({data}) => this.addTag(data))
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.getTagFromDataBaseByName(name)
                                .then(tag => this.addTag(tag))
                        } else {
                            flash("Error while adding tag to database", "error")
                        }

                        this.addingToDatabase = false

                        this.reset()
                    })
            },

            getTagFromDataBaseByName(name) {
                return new Promise((resolve, reject) => {
                    axios.get(`${this.tagUrl}/tag-by-name/${name}`)
                        .then(({data}) => {
                            resolve(data)
                        })
                        .catch(error => {
                            flash("Error while fetching tag", "error")
                            reject()
                        })
                })
            },

            removeTag(index, tag) {
                this.tags.splice(index, 1)

                this.$emit('tag-removed', tag)
            },

            tagIsInSuggestion() {
                return this.suggestions.filter(t => t.name === this.tag).length > 0
            },

            alreadyTagged(tag) {
                return this.tags.filter(t => t.slug === tag.slug).length > 0
            },

            reset() {
                this.tag = ''
                this.suggestions = []
                this.focusedIndex = -1
            }

        }
    }
</script>