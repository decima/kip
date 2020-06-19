const state = {
    navBarWidth: '250px',
    navBarCollapsed: false,
    navBarDrawerOpened: false
};

const mutations = {
    setNavBarWidth(state, value) {
        state.navBarWidth = value;
    },
    setNavBarCollapsed(state, value) {
        state.navBarCollapsed = value;
    },
    setNavBarDrawerOpened(state, value) {
        state.navBarDrawerOpened = value;
    },
};

const actions = {

};

const getters = {
    getNavBarWidth: state => state.navBarWidth,
    getNavBarCollapsed: state => state.navBarCollapsed,
    getNavBarDrawerOpened: state => state.navBarDrawerOpened,
};

export default {
    state,
    getters,
    actions,
    mutations
};
