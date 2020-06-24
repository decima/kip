const state = {
    navBarWidth: '250px',
    navBarCollapsed: false,
    navBarDrawerOpened: false,
    tocCollapsed: false,
    tocDrawerOpened : false
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
    setTocCollapsed(state, value) {
        state.tocCollapsed = value;
    },
    setTocDrawerOpened(state, value) {
        state.tocDrawerOpened = value;
    },
};

const actions = {

};

const getters = {
    getNavBarWidth: state => state.navBarWidth,
    getNavBarCollapsed: state => state.navBarCollapsed,
    getNavBarDrawerOpened: state => state.navBarDrawerOpened,
    getTocCollapsed: state => state.tocCollapsed,
    getTocDrawerOpened: state => state.tocDrawerOpened,
};

export default {
    state,
    getters,
    actions,
    mutations
};
