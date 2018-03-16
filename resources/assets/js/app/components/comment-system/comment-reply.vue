<template>
	<div class="" v-if="show && ! authorize('owns', this.comment)">

		<!--- Reply form --->
		<form @submit.prevent="addReply()" class="mt-2">

			<p class="c-red fsz-sm fs-i mb-0"
			   v-if="reply.errors.has('body')"
			   v-text="reply.errors.get('body')"
			></p>
			<wysiwyg :id="'reply-comment-wysiwyg-'+comment.id"
			         v-model="reply.body"
			         :value="reply.body"
			         placeholder="Any suggestions ?">
			</wysiwyg>

			<div class="mt-3 mb-1">
				<p class="c-red fsz-sm fs-i mb-0"
				   v-if="reply.errors.has('gRecaptchaResponse')"
				   v-text="reply.errors.get('gRecaptchaResponse')"
				></p>
				<recaptcha :data-reset="show" @verified="recaptchaVerified"></recaptcha>
			</div>

			<div class="d-f mt-2">

				<button type="submit"
				        class="btn btn-primary btn-sm mr-3 tt-u ls-12"
				        :class="{ 'loader' : processingReply }"
				>Reply
				</button>

				<button type="button"
				        class="btn btn-secondary btn-sm tt-u ls-12"
				        @click="reset()"
				>Cancel
				</button>

			</div>

		</form>

	</div>
</template>

<script>
    import Form from '../../../common/Form';

    export default {
        name: "reply",

        props: ['comment', 'show'],

        data() {
            return {
                reply: new Form({
                    body: null,
                    parent_id: this.comment.id,
                    gRecaptchaResponse: null
                }),
                bodyError: null,
                captchaError: null,
                processingReply: false
            }
        },

        methods: {

            addReply() {
                this.processingReply = true
                this.reply.parent_id = this.comment.id
                this.reply.errors.clear()

                this.reply.post('/comments/replies')
                    .then(res => {
                        this.comment.replies.splice(0, 0, res)
                        this.processingReply = false
                        Event.fire('comment-added')
                        this.reset()
                        flash('Reply added')
                    })
                    .catch(error => this.processingReply = false)

            },

            recaptchaVerified(data) {
                this.reply.gRecaptchaResponse = data.response
                this.reply.errors.clear('gRecaptchaResponse')
            },

            reset() {
                this.reply.body = null
                this.reply.gRecaptchaResponse = null
                this.$emit('reset-reply-form')
            },

        }
    }
</script>]