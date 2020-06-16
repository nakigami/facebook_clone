import Vue from 'vue';
import VueRouter from "vue-router";
import NewsFeed from "./views/NewsFeed";
import UserShow from "./views/users/Show";
Vue.use(VueRouter);

export default new VueRouter({
    mode:'history',
    routes: [
        {
            path:'/', name: 'home', component: NewsFeed,
            meta: {
                title: 'News Feed'
            }
        },
        {
            path:'/users/:userid', name: 'user.show', component: UserShow,
            meta: {
                title: 'Profile'
            }
        }
    ]
})
