<script>
    import _ from 'lodash'
    import Form from '../../common/Form'
    import {ZoomCenterTransition} from 'vue2-transitions'
    import moment from 'moment'
    import Utils from "../../common/Utils";

    export default {
        name: "manage-post",

        props: ['postUrl', 'postData', 'isPage', 'postType', 'defaultSocialAutoPost', 'republishToSocialMediaUrl'],

        components: {
            ZoomCenterTransition
        },

        data() {
            return {
                editSlug: false,
                saving: false,
                republishingToSocial: false,
                originalSlug: this.postData ? this.postData.slug : null,
                post: new Form({
                    id: null,
                    title: null,
                    slug: null,
                    body: null,
                    excerpt: null,
                    post_type: this.postType,
                    post_format: this.postData ? this.postData.post_format : 'standard',
                    publish_at: null,
                    created_at: null,
                    updated_at: null,
                    comments_count: null,
                    category_slug: null,
                    tags: [],
                    featured_image_id: null,
                    featured_image: {
                        variations: {
                            thumbnail: {
                                path: null
                            }
                        },
                    },
                    metas: {
                        description: null,
                        robots: 'index, follow',
                        auto_post_facebook: this.defaultSocialAutoPost.facebook === 'enable',
                        auto_post_twitter: this.defaultSocialAutoPost.twitter === 'enable'
                    },
                })
            }
        },

        mounted() {

            if (this.postData) {
                this.assignPostToForm(this.postData)
            }

            Event.listen('featured-image-selected', this.featuredImageSelected)

            this.$watch('editSlug', val => {
                if (!val) {
                    this.post.slug = this.originalSlug;
                }
            })
        },

        methods: {

            publish(update = false) {
                this.post.publish_at = moment().format('YYYY-MM-DD HH:mm:ss')

                this.$nextTick(
                    () => update ? this.update() : this.save()
                )
            },

            unPublish() {
                this.post.publish_at = null

                this.update()
            },

            draft() {
                this.post.publish_at = null

                this.save()
            },

            save() {
                this.saving = true

                this.post.post(this.postUrl)
                    .then(post => window.location.href = this.postUrl + '/' + post.id + '/edit')
                    .catch(err => this.saving = false)
            },

            update() {
                this.post.patch(this.postUrl)
                    .then(post => {
                        this.assignPostToForm(post)
                        flash("Post updated")
                    })
            },

            deletePost() {
                let type = this.isPage ? 'page' : 'post'
                let ask = `<h4 class='mb-0'>Are you sure ?</h4></n> You want to <span class='tt-u ls-12'>delete</span> this ${type}`

                this.$confirm(ask)
                    .then(res => {
                        axios.delete(this.postUrl)
                            .then(res => {
                                flash("Post deleted"
                                )
                                let type = this.isPage ? 'pages' : 'posts'

                                window.location.href = `/dashboard/${type}`
                            })
                    })

            },

            selectFeaturedImage() {
                Event.fire('select-featured-image', {
                    type: 'image',
                    multiple: false,
                    callback: this.featuredImageSelected
                })
            },

            featuredImageSelected(image) {
                this.post.featured_image_id = image.id

                if (!this.post.featured_image) {
                    this.post.featured_image = Utils.addProps({}, 'variations.thumbnail.path', image.variations.thumbnail.path)
                } else {
                    this.post.featured_image.variations.thumbnail.path = image.variations.thumbnail.path
                }
            },

            removeFeaturedImage() {
                this.post.featured_image_id = null
                this.post.featured_image.variations.thumbnail.path = null
            },

            tagSelected(tag) {
                if (!this.post.tags.includes(tag.id)) {
                    this.post.tags.push(tag.id)
                }
            },

            tagRemoved(tag) {
                let index = this.post.tags.indexOf(tag.id)

                if (index >= 0) {
                    this.post.tags.splice(index, 1)
                }

            },

            assignPostToForm(postData) {
                let tags = []
                !this.isPage ? postData.tags.forEach(tag => tag.id ? tags.push(tag.id) : ''
                    ) :
                    ''

                let metasByKey = _.keyBy(postData.metas, 'key');

                let metas = {}
                _.each(metasByKey, meta => {
                        metas[meta.key] = meta.value
                    }
                )

                Object.assign(this.post, postData, {
                    tags: tags,
                    metas: metas
                })

                this.originalSlug = postData.slug

                this.editSlug = false
            },

            republishingToSocialProvider(provider) {
                this.republishingToSocial = true
                
                let url = this.republishToSocialMediaUrl.replace('%postId%', this.post.id)
                url = url.replace('%provider%', provider)

                axios.patch(url, {})
                    .then( response  => {
                        this.republishingToSocial = false
                        flash(`Posted to ${provider}`)
                    })
                    .catch(err => {
                        this.republishingToSocial =  false
                        console.log(err)
                    })
            }

        }

    }
</script>