<template>
    <ul class="list-unstyled mt-4">
        <comment :comment="comment" v-for="(comment, index) in comments" :comment-index="index" :key="comment.id" @delete-comment="deleteComment"></comment>
    </ul>
</template>

<script>
    export default {
        name: "comments",

        props: ['comments'],
        
        methods: {
            
            deleteComment(data) {
                this.$delete(this.comments, data.commentIndex)
                
                Event.fire('comment-deleted', {
                    count: data.repliesCount + 1
                })
            }
            
        }
    }
</script>