<script>
    import TaxonomiesList from './taxonomies-list'
    import Form from '../../common/Form'
    import _ from 'lodash'

    export default {
        name: "taxonomies",

        props: ['taxonomiesUrl', 'taxonomySingular', 'taxonomyPlural'],

        components: {
            TaxonomiesList
        },

        data() {
            return {
                taxonomies: [],
                flatTaxonomies: [],
                fetching: true,
                count: 0,
                taxonomy: new Form({
                    id: null,
                    name: null,
                    slug: null,
                    parent_id: null,
                    description: null,
                    processing: false,
                })
            }
        },

        mounted() {
            Event.listen('edit-taxonomy', (data) => Object.assign(this.taxonomy, data.taxonomy))
            Event.listen('taxonomy-deleted', () => this.count = this.count - 1)
            this.fetchTaxonomies()
        },

        methods: {

            flattenTaxonomies() {
                this.taxonomies = _.sortBy(this.taxonomies, t => t.name)

                this.flatTaxonomies = _.sortBy([].flatResult(this.taxonomies), t => t.name.toLowerCase())

                this.count = this.flatTaxonomies.length
            },

            fetchTaxonomies() {
                axios.get(this.taxonomiesUrl)
                    .then(response => {
                        this.taxonomies = response.data

                        this.flattenTaxonomies()

                        this.fetching = false

                        if (this.taxonomySingular === 'tag') {
                            this.count = this.taxonomies.length
                        }

                    })
                    .catch(error => {
                        console.log(error)
                        this.fetching = false
                    })
            },

            add() {
                this.taxonomy.processing = true
                
                console.log(this.taxonomy)

                this.taxonomy.post(this.taxonomiesUrl)
                    .then(newTaxonomy => {

                        this.taxonomy.processing = false

                        this.count = this.count + 1

                        if (!newTaxonomy.parent_id) {
                            this.taxonomies.push(newTaxonomy)
                        } else {
                            let parent = _.find(this.taxonomies, c => c.id === newTaxonomy.parent_id)

                            parent.children.push(newTaxonomy)
                        }

                        this.taxonomy.parent_id = null

                        this.$nextTick(() => this.flattenTaxonomies())

                        flash("Taxonomy added")
                    })
                    .catch(error => {
                        this.taxonomy.processing = false
                        flash('Error while adding taxonomy', 'error')
                    })
            },

            update() {
                this.taxonomy.processing = true

                this.taxonomy.patch(`${this.taxonomiesUrl}/${this.taxonomy.id}`)
                    .then(response => {
                        this.$scrollTo(`#taxonomy-${response.slug}-${response.id}`)
                        this.taxonomy.processing = false
                        this.fetchTaxonomies()
                    })
                    .catch(error => {
                        this.taxonomy.processing = false
                        console.log(error)
                    })
            },

            reset() {
                Object.assign(this.taxonomy, {
                    id: null,
                    name: null,
                    slug: null,
                    parent_id: null,
                    description: null,
                    processing: false
                })
            },
        },
    }
</script>