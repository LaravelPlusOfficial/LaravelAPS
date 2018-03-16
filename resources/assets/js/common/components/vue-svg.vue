<template>
    <svg :width="sw" :height="sh" :class="classes" v-cloak>
        <use :xlink:href="'#' + name"></use>
    </svg>
</template>

<script>
    export default {
        name: "vue-svg",

        props: [
          'name',
          'width',
          'height',
          'classes',
          'square'
        ],

        data() {
          return {
              sw: '16',
              sh: '16'
          }
        },

        mounted() {
            if(this.square) {

                this.sw = this.sh = parseInt(this.square)

            } else {

                this.sw = this.width

                this.sh = this.height

            }
        }
    }
</script>