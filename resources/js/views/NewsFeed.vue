<template>
    <div class="flex flex-col items-center py-4">
        <NewPost />
        <p v-if="loading">
            <ClipLoader color="#3498db"/>
        </p>
        <Post v-else v-for="post in posts.data" :key="post.data.post_id" :post="post"/>
    </div>
</template>

<script>
    import NewPost from "../components/NewPost";
    import ClipLoader from 'vue-spinner/src/ClipLoader.vue'
    import Post from "../components/Post";
    export default {
        name: "NewsFeed",
        components: {Post, NewPost,ClipLoader },
        data() {
            return {
                posts: null,
                loading: true
            }
        },
        mounted(){
            axios.get('/api/posts')
                .then(res => {
                    this.posts = res.data
                })
                .catch(err => {
                    console.log(err.message)
                });
            setInterval(() => {
                this.loading = false
            });
        }

    }
</script>

<style scoped>

</style>
