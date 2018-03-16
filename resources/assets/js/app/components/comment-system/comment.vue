<template>
    <li class="media mb-3 p-rel" :id="'comment-' + comment.id">

        <img class="round sq-50 mr-3" :src="comment.owner.avatar" v-if="comment.owner.avatar">

        <img class="round sq-50 mr-3" :src="defaultAvatar" v-if="!comment.owner.avatar && defaultAvatar">

        <div class="mr-3" v-if="!comment.owner.avatar && !defaultAvatar">
            <vue-svg name="icon-user" square="30" classes="fill-gray"></vue-svg>
            <!--<blinking-user size="50"></blinking-user>-->
        </div>

        <div class="media-body">

            <h4 class="d-f m-0">
                <span class="fsz-xs fw-700 ls-12 tt-u mr-2" v-text="comment.owner.name"></span>
                <time class="c-gray fsz-xs">{{ comment.created_at | timeAgo }}</time>
            </h4>

            <div class="" v-if="comment.status !== 'spam'">
                <div class="comment-body" v-html="comment.body" v-show="! editing"></div>
            </div>

            <div class="" v-else>
                <div class="comment-body c-red" v-show="! editing">
                    Comment is pending approval
                </div>
            </div>

            <!--- Editor Toolset --->
            <div class="toolset mt-2 d-f ac-c ai-c mb-1" v-if="showEditToolset">

                <!--- Edit Button --->
                <a href="#"
                   class="d-f ai-c fill-gray c-gray fill-primary-hv c-primary-hv trans-eio mr-2"
                   @click.prevent="edit()"
                   v-if="authorize('owns', this.comment)"
                >
                <span class="d-f ai-c mr-1">
                    <vue-svg name="icon-edit" square="16"></vue-svg>
                </span>
                    <span class="d-f fsz-xs ls-12 tt-u">Edit</span>
                </a>

                <!--- Delete button --->
                <a href="#"
                   class="d-f ai-c fill-gray c-gray fill-primary-hv c-primary-hv trans-eio mr-2 "
                   @click.prevent="deleting = true"
                   v-if="authorize('owns', this.comment)"
                >
                <span class="d-f ai-c mr-1 ml-1">
                    <vue-svg name="icon-delete" square="16"></vue-svg>
                </span>
                    <span class="d-f fsz-xs ls-12 tt-u">delete</span>
                </a>

                <!--- Reply button --->
                <a href="#"
                   class="d-f ai-c fill-gray c-gray fill-primary-hv c-primary-hv trans-eio mr-2"
                   @click.prevent="showReplyForm()"
                   v-if="! authorize('owns', this.comment)"
                >
                <span class="d-f ai-c mr-1">
                    <vue-svg name="icon-arrow-back-outline" square="16"></vue-svg>
                </span>
                    <span class="d-f fsz-xs ls-12 tt-u">Reply</span>
                </a>

            </div>

            <!--- Edit Form --->
            <form @submit.prevent="update()" class="mt-2" v-if="authorize('owns', this.comment) && editing">

                <p class="c-red fsz-sm fs-i mb-0"
                   v-if="form.errors.has('body')"
                   v-text="form.errors.get('body')"
                ></p>
                <wysiwyg :id="'edit-comment-wysiwyg-'+comment.id"
                         v-model="form.body"
                         :value="comment.body"
                         placeholder="Have something say?">
                </wysiwyg>

                <!--- reCaptcha --->
                <p class="c-red fsz-sm fs-i mb-0"
                   v-if="form.errors.has('gRecaptchaResponse')"
                   v-text="form.errors.get('gRecaptchaResponse')"
                ></p>
                <div class="mt-2 mb-2">
                    <recaptcha :data-reset="! editing" @verified="recaptchaVerified"></recaptcha>
                </div>

                <div class="d-f mt-2">
                    <button type="submit"
                            class="btn btn-primary btn-sm mr-3 tt-u ls-12"
                    >
                        <loader height="13px" :loading="processingUpdate" color="white"></loader>
                        <span v-show="! processingUpdate">update</span>
                    </button>
                    <button type="button"
                            class="btn btn-secondary btn-sm tt-u ls-12"
                            @click="editing = null, form.gRecaptchaResponse = null"
                    >Cancel
                    </button>
                </div>

            </form>

            <!--- Delete Form --->
            <form action="" @submit.prevent="deleteComment()" v-if="authorize('owns', this.comment) && deleting">

                <p class="c-red mb-1">Are you sure you want to delete this comment</p>
                <!--- reCaptcha --->
                <div class="mt-2 mb-2">
                    <recaptcha :data-reset="! deleting" @verified="recaptchaVerified"></recaptcha>
                </div>

                <div class="d-f mt-2">
                    <button type="submit"
                            class="btn btn-primary btn-sm mr-3 tt-u ls-12"
                    >
                        <span>Delete</span>
                    </button>
                    <button type="button"
                            class="btn btn-secondary btn-sm tt-u ls-12"
                            @click="deleting = false, form.gRecaptchaResponse = null"
                    >Cancel
                    </button>
                </div>

            </form>

            <!--- Reply form --->
            <comment-reply :show="replying" :comment="comment" @reset-reply-form="replying = false"></comment-reply>

            <!--- Replies --->
            <comments :comments="comment.replies" v-if="comment.replies.length > 0"></comments>

        </div>

    </li>
</template>

<script>
    import Form from '../../../common/Form';
    import Util from "../../../common/Utils";
    import axios from 'axios'

    export default {
        name: "comment",

        props: ['comment', 'commentIndex'],

        data() {
            return {
                editing: false,
                deleting: false,
                replying: false,
                defaultAvatar: App.default_avatar,
                form: new Form({
                    id: this.comment.id,
                    body: null,
                    gRecaptchaResponse: null
                }),
                processingUpdate: false
            }
        },

        mounted() {
            this.scrollToCommentIfRequired()
        },

        computed: {

            showEditToolset() {
                if ( (!this.editing && !this.replying && !this.deleting) && (this.comment.status !== 'spam') ) {
                    return true;
                }

                return false;
            }

        },

        methods: {

            edit() {
                this.form.body = this.comment.body
                this.editing = true
            },

            update() {
                this.processingUpdate = true

                this.form.patch(`/comments/${this.comment.id}`)
                    .then(res => {
                        this.comment.body = res.body
                        this.processingUpdate = false
                        this.editing = false
                        flash('Updated')
                    })
                    .catch(error => {
                        this.processingUpdate = false
                    })
            },

            deleteComment() {

                axios.delete(`/comments/${this.comment.id}`, {
                    data: {
                        gRecaptchaResponse: this.form.gRecaptchaResponse
                    }
                })
                    .then(response => {

                        this.$emit('delete-comment', {
                            commentIndex: this.commentIndex,
                            repliesCount: Util.countObjectDepth(this.comment, 'replies')
                        })

                        this.deleting = false

                        flash('Deleted')
                    })
                    .catch(error => {
                        this.deleting = false
                        console.log(error)
                    })
            },

            recaptchaVerified(data) {
                this.form.gRecaptchaResponse = data.response
            },

            scrollToCommentIfRequired() {

                this.$nextTick(() => {

                    if (`comment-${this.comment.id}` === Util.getQueryString('go-to')) {
                        this.$scrollTo(this.$el)
                    }

                })

            },

            showReplyForm() {
                if (!this.signedIn) {

                    let url = '/login?backUrl=' + Util.urlEncode(`${window.App.currentUri}?go-to=${this.comment.id}`)

                    window.location.href = url

                } else if (!this.authorize('owns', this.comment)) {
                    this.replying = true
                }
            },

        },

        watch: {

            editing(val) {
                if (val) {
                    this.deleting = false
                    this.replying = false
                }
            },

            deleting(val) {
                if (val) {
                    this.editing = false
                    this.replying = false
                }
            },

            replying(val) {
                if (val) {
                    this.editing = false
                    this.deleting = false
                }
            }

        }
    }
</script>