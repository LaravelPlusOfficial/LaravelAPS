<template>
    <div class="container-fluid mb-7 pb-2">

        <div class="row">

            <div class="col-lg-12">

                <!--- Loader --->
                <div class="d-f jc-c mb-5" v-if="loading">
                    <loader></loader>
                </div>

                <!--- Comment Heading --->
                <div class="mb-3" v-if="!loading">
                    <h3 class="bdB pb-2 fsz-sm tt-u ls-12 c-gray-dark">
                        <span v-text="commentsCount">4</span>
                        <span v-text="commentsCount > 0 ? 'Comments' : 'Comment'"></span>
                    </h3>
                </div>

                <!--- New Comment Form --->
                <div class="" v-if="!loading">

                    <!--- Login to post comment --->
                    <div class="mb-3" v-if="!signedIn">
                        <a :href="afterLoginRedirect()"
                           class="btn btn-primary btn-sm tt-u ls-12">
                            Login to post comment
                        </a>
                    </div>

                    <div class="" v-else>

                        <button type="submit"
                                class="btn btn-primary btn-sm mr-3 tt-u ls-12"
                                @click="commenting = true"
                                v-if="! commenting"
                        >Comment
                        </button>

                        <form @submit.prevent="" class="mt-2" v-if="commenting">

                            <p class="c-red fsz-sm fs-i mb-0"
                               v-if="newComment.errors.has('body')"
                               v-text="newComment.errors.get('body')"
                            ></p>

                            <wysiwyg :id="'new-comment-wysiwyg'"
                                     v-model="newComment.body"

                                     placeholder="Have something say?">
                            </wysiwyg>

                            <!--- reCaptcha --->
                            <p class="c-red fsz-sm fs-i mb-0 mt-1"
                               v-if="newComment.errors.has('gRecaptchaResponse')"
                               v-text="newComment.errors.get('gRecaptchaResponse')"
                            ></p>
                            <div class="mt-2 mb-2">
                                <recaptcha v-if="commenting" :data-reset="! commenting"
                                           @verified="recaptchaVerified"></recaptcha>
                            </div>

                            <div class="d-f mt-2">

                                <button type="submit"
                                        class="btn btn-primary btn-sm mr-3 tt-u ls-12"
                                        :class="{loader : addingComment}"
                                        @click="addComment()">
                                    Add Comment
                                </button>

                                <button type="button"
                                        class="btn btn-secondary btn-sm tt-u ls-12"
                                        @click.prevent="resetForm()">
                                    <span v-text="newComment.body ? 'Clear' : 'Close'"></span>
                                </button>

                            </div>

                        </form>


                    </div>
                </div>

                <!--- Comments --->
                <div class="mt-4" v-if="comments.length > 0">
                    <comments :comments="comments"></comments>
                </div>

            </div>

        </div>

    </div>
</template>

<script>
    import Form from '../../../common/Form'

    export default {
        name: "comment-system",

        props: ['commentUrl', 'initialCommentsCount', 'postId'],

        data() {
            return {
                loading: true,
                comments: [],
                commentsCount: this.initialCommentsCount,
                commenting: false,
                newComment: new Form({
                    body: null,
                    post_id: this.postId,
                    gRecaptchaResponse: null
                }),
                addingComment: false
            }
        },

        mounted() {
            this.fetchComments()

            Event.listen('comment-deleted', data => {
                this.commentsCount = this.commentsCount > 0 ? this.commentsCount - data.count : 0
            })

            Event.listen('comment-added', data => ++this.commentsCount)
        },

        methods: {
            fetchComments() {
                axios.get('/comments', {
                    params: {
                        post_id: this.postId
                    }
                })
                    .then(({data}) => {
                        this.comments = data
                        this.loading = false
                    })
                    .catch(error => {
                        this.comments = []
                        this.loading = false
                    })
            },

            addComment() {
                this.newComment.post_id = this.postId
                this.addingComment = true

                this.newComment.post(this.commentUrl)
                    .then(comment => {
                        this.comments.splice(0, 0, comment)
                        ++this.commentsCount
                        this.resetForm()
                        flash('Comment added')
                    })
                    .catch(error => this.addingComment = false)
            },

            recaptchaVerified(data) {
                this.newComment.gRecaptchaResponse = data.response
                this.newComment.errors.clear('gRecaptchaResponse')
            },

            resetForm() {
                this.addingComment = false
                this.commenting = false
                this.newComment.body = null
                this.newComment.errors.clear()
            },

            afterLoginRedirect() {
                return `/login?backUrl=${App.currentUri}`
            },
        }
    }
</script>