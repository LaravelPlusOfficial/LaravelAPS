<template>
    <div>
        <div class="d-f fxd-c ac-c ai-c mt-3">

            <img :src="avatar" class="avatar avatar-img" v-if="avatar">

            <img :src="defaultAvatar" class="avatar avatar-img" v-if="!avatar && defaultAvatar">

            <div v-if="! avatar && ! defaultAvatar"
                 class="d-f jc-c bgc-gray-light fill-gray m-2 avatar avatar-svg ac-c ai-c">
                <vue-svg name="icon-user" square="60"></vue-svg>
            </div>

        </div>

        <div class="d-f jc-c mt-3 mb-4" v-if="updating">
            <loader height="18px" width="2px"></loader>
        </div>

        <div class="d-f jc-c mt-3 mb-4 fill-gray" v-if="!updating && canUpdate">

            <button type="button"
                    @click.prevent="$refs.input.click()"
                    v-if="!avatar"
                    class="bg-n p-0 lh-1 bd-n otl-n-fc cur-p fill-primary-hv">
                <vue-svg name="icon-upload" square="20"></vue-svg>
            </button>

            <button type="button"
                    @click.prevent="$refs.input.click()"
                    v-if="avatar"
                    class="bg-n p-0 lh-1 bd-n otl-n-fc cur-p fill-primary-hv">
                <vue-svg name="icon-edit" square="20"></vue-svg>
            </button>

            <button type="button"
                    @click.prevent="remove()"
                    v-if="avatar"
                    class="bg-n p-0 lh-1 bd-n ml-3 otl-n-fc cur-p fill-primary-hv">
                <vue-svg name="icon-delete" square="20"></vue-svg>
            </button>
        </div>

        <input type="file" style="display: none" accept="image/*" ref="input" @change="processImage($event)">
    </div>
</template>

<script>
    export default {
        name: "avatar",

        props: ['src', 'avatarUrl'],

        data() {
            return {
                avatar: this.src,
                updating: false,
                defaultAvatar: App.default_avatar
            }
        },

        computed: {
            canUpdate() {
                return this.authorize(user => user.id === App.user.id);
            }
        },

        methods: {

            processImage($event) {
                this.updating = true
                let data = new FormData();
                data.append('avatar', $event.target.files[0]);

                axios.post(this.avatarUrl, data)
                    .then(({data}) => {
                        //console.log(data)
                        this.avatar = data.avatar
                        this.updating = false
                        flash('Avatar updated')
                        this.$refs.input.value = null
                    });

            },

            remove() {

                axios.delete(this.avatarUrl)
                    .then(({data}) => {
                        console.log(data);
                        this.avatar = data.avatar
                        flash('Avatar deleted')
                        this.$refs.input.value = null
                    })
                    .catch(err => console.log(err))
            }

        }
    }
</script>