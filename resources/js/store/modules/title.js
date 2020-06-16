const state = {
    title: 'Welcome'
};

const mutations = {
    setTitle(state, title){
        state.title = title + " | Facebook";
        document.title = state.title;
    }
};

const getters = {
    getTitle(state){
        return state.title
    }
};

const actions = {
    // Change title depending on the page
    setPageTitle({commit}, title){
        commit('setTitle' , title)
    }
};

export default {
    state, getters, actions, mutations
}
