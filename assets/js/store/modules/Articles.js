import axios from "axios";

const state = {
    articlesTree: null,
    currentArticle: null,
};

const mutations = {
    setArticlesTree(state, value) {
        state.articlesTree = value;
    },
    setCurrentArticle(state, value) {
        state.currentArticle = value;
    },
    setCurrentArticleMarkdownContent(state, value){
        state.currentArticle.file.markdownContent = value;
    }
};

const actions = {
    async loadArticlesTree({commit}) {
        const articlesTree = (await axios.get(Router.url("articles_tree"))).data;
        commit("setArticlesTree", articlesTree);
        return articlesTree;
    },
    async loadCurrentArticleFromPath({commit}, webpath) {
        try {
            const currentArticle = (await axios.get(Router.url("knowledge_read", {webpath}))).data;
            commit("setCurrentArticle", currentArticle);
            return currentArticle;
        } catch(e){
            //handle when the file doesn't exist
            if(e.response.status === 404){
                commit("setCurrentArticle", null);
                return null;
            }
        }
    },
    async deleteArticle({commit}, webpath) {
        return await axios.get(Router.url("knowledge_delete", {webpath}));
    },
    async loadArticleToEditFromPath({commit}, webpath) {
        const articleToEdit = (await axios.get(Router.url("knowledge_edit", {webpath}))).data;
        commit("setCurrentArticle", articleToEdit);
        return articleToEdit;
    },
    async saveArticle({commit}, payload){
        return await axios.put(Router.url("knowledge_update", {webpath: payload.webpath}), payload.content);
    }
};

const getters = {
    getArticlesTree: state => state.articlesTree,
    getCurrentArticle: state => state.currentArticle,
};

export default {
    state,
    getters,
    actions,
    mutations
};
