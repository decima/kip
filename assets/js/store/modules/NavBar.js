const state = {
    navBarWidth: '250px',
};

const mutations = {
    setNavBarWidth(state, value) {
        state.navBarWidth = value;
    },
};

const actions = {

};

const getters = {
    getNavBarWidth: state => state.navBarWidth,
};

export default {
    state,
    getters,
    actions,
    mutations
};
