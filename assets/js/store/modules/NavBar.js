const state = {
    navBarWidth: '250px',
    navBarCollapsed: false,
    navBarDrawerOpened: false,
    tocCollapsed: false,
    tocDrawerOpened : false,
    windowWidth : window.innerWidth
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
    setWindowWidth(state, value) {
        state.windowWidth = value;
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
    getWindowWidth: state => state.windowWidth,
};

export default {
    state,
    getters,
    actions,
    mutations
};
