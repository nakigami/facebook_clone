<template>
    <div class="flex flex-col flex-1 h-screen overflow-y-hidden w-full">
        <Nav/>
        <div class="flex overflow-y-hidden flex-1">
            <Sidebar />
            <div class="w-2/3 overflow-x-hidden">
                <router-view/>
            </div>
        </div>
    </div>

</template>

<script>
    import Nav from "./Nav";
    import Sidebar from "./Sidebar";
    import NewsFeed from "../views/NewsFeed";
    import Show from '../views/users/Show';
    export default {
        name: "App",
        components: {
            NewsFeed,
            Nav,
            Sidebar,
            Show
        },
        created(){
            this.$store.dispatch('setPageTitle', this.$route.meta.title)
        },
        mounted() {
            this.$store.dispatch('getAuthenticatedUser')
        },
        watch: {
            $route(to, from){
                this.$store.dispatch('setPageTitle', to.meta.title)
            }
        }
    }
</script>

<style scoped>

</style>
