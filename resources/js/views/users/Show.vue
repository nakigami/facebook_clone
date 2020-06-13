<template>
    <div class="flex flex-col items-center">
        <div class="relative">
            <div class="w-100 h-64 overflow-hidden">
                <img class="w-full object-cover" src="https://learn.zoner.com/wp-content/uploads/2018/08/landscape-photography-at-every-hour-part-ii-photographing-landscapes-in-rain-or-shine.jpg" />
            </div>
            <div class="absolute flex items-center bottom-0 left-0 -mb-10 ml-4">
                <img class="w-40 h-40 rounded-full object-cover border-4 border-b-2 border-white p-2 hover:bg-white" src="https://avatarfiles.alphacoders.com/759/75944.jpg" />
                <p v-if="userLoading === false " class="text-white ml-2">{{ user.data.attributes.name.toUpperCase()}}</p>
            </div>
        </div>
            <p v-if="postLoading">
                <ClipLoader color="#3498db"/>
            </p>
            <Post v-else v-for="post in posts.data" :post="post" :key="post.data.post_id"/>
    </div>



</template>

<script>
    import Post from "../../components/Post";
    import ClipLoader from 'vue-spinner/src/ClipLoader.vue';
    export default {
        name: "Show",
        components: {Post, ClipLoader},
        data(){
            return {
                user: null,
                posts:null,
                postLoading: true,
                userLoading: true
            }
        },
        mounted() {

            // Get all users
            axios.get('/api/users/'+ this.$route.params.userid)
            .then((res) => {
                this.user = res.data;
            })
            .catch((err) => {
                console.log('Unable to fetch user from the server');
            })
            .finally(() => {
                this.userLoading = false;
            });

            // Get User Posts
            axios.get('/api/users/'+this.$route.params.userid+'/posts')
            .then(res => {
                this.posts = res.data;
            })
            .catch((err) => {
                console.log('Unable to fetch user posts from the server');
            })
            .finally(() => {
                this.postLoading = false;
            });
        }
    }
</script>

<style scoped>

</style>
