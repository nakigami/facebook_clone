const state = {
    user: null,
    userLoading: true
};

const actions = {
    getAuthenticatedUser({commit,state})
    {
        // Fetch the authenticated user
        axios.get('/api/auth-user')
            .then((res) => {
                console.log("voila " + res.data);
                commit('setUser' , res.data);
            })
            .catch(err => {
                console.log(err.message)
            })
            .finally(() => {
                commit('changeLoadingStage')
            });
    }
};

const mutations = {
   setUser(state, user){
       state.user = user;
   },
   changeLoadingStage(state){
       state.userLoading = false;
   }
};

const getters = {
    getUser(state)
    {
        return state.user
    },
    getLoadingState(state){
        return state.userLoading
    }
};

export default
{
    state, actions, mutations, getters
}
