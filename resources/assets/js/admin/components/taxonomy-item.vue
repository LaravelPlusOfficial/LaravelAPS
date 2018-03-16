<template>
    <li :id="`taxonomy-${taxonomy.slug}-${taxonomy.id}`">
        <div class="mb-2">
            <div class="d-f ai-c">
                <h5 class="mb-0" v-text="taxonomy.name"></h5>
                <div class="d-f" v-if="checkIfEditable()">
                    <a href="#" class="ml-2" @click.prevent="edit()">
                        <vue-svg name="icon-edit" :width="12" :height="12" classes="fill-primary-hv"></vue-svg>
                    </a>
                    <a href="#"
                       class="ml-2"
                       @click.prevent="deleteTaxonomy()">
                        <vue-svg name="icon-delete" :width="12" :height="12" classes="fill-primary-hv"></vue-svg>
                    </a>
                    <!--v-if="checkIfDeletable()"-->
                </div>
            </div>
            <p v-text="taxonomy.description"></p>
        </div>

        <taxonomies-list :taxonomies="taxonomy.children"
                         :taxonomies-url="taxonomiesUrl"
                         v-if="taxonomy.children && taxonomy.children.length > 0"></taxonomies-list>

    </li>
</template>

<script>
    export default {
        name: "taxonomy-item",

        props: ['taxonomy', 'taxonomyIndex', 'taxonomiesUrl', 'taxonomySingular'],

        methods: {

            edit() {

                this.$scrollTo('#create-new-taxonomy')

                Event.fire('edit-taxonomy', {
                    taxonomy: this.taxonomy,
                })
            },

            deleteTaxonomy() {
                console.log(`${this.taxonomiesUrl}/${this.taxonomy.id}`)
                axios.delete(`${this.taxonomiesUrl}/${this.taxonomy.id}`)

                    .then(response => {
                        flash('Category deleted')

                        this.$parent.$options.propsData.taxonomies.splice(this.taxonomyIndex, 1)

                        Event.fire('taxonomy-deleted')

                    })
                    .catch(error => {
                        flash(error.response.data, 'error')
                    })

            },

            // checkIfDeletable() {
            //     if (this.taxonomySingular === 'category') {
            //         return this.taxonomy.children && this.taxonomy.children.length === 0 && (this.taxonomy.slug !== 'uncategorized')
            //     }
            //
            //     return true
            // },

            checkIfEditable() {
                return this.taxonomy.slug !== 'uncategorized'
            }

        }
    }
</script>