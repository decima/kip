import axios from "axios";

const state = {
    articlesTree: null,
    currentArticle: null,
    articleToEdit: null
};

const mutations = {
    setArticlesTree(state, value) {
        state.articlesTree = value;
    },
    setCurrentArticle(state, value) {
        state.currentArticle = value;
    },
    setArticleToEdit(state, value) {
        state.articleToEdit = value;
    }
};

const actions = {
    async loadArticlesTree({commit}) {
        const articlesTree = (await axios.get(Router.url("articles_tree"))).data;
        commit("setArticlesTree", articlesTree);
        return articlesTree;
    },
    async loadCurrentArticleFromPath({commit}, path) {
        try {
            const currentArticle = (await axios.get(Router.url("knowledge_read", {webpath: path}))).data;
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
    async deleteArticle({commit}, path) {
        return await axios.get(Router.url("knowledge_delete", {webpath: path}));
    },
    async loadArticleToEditFromPath({commit}, path) {
        const articleToEdit = (await axios.get(Router.url("knowledge_edit", {webpath: path}))).data;
        commit("setArticleToEdit", articleToEdit);
        return articleToEdit;
    }
};

const getters = {
    getArticlesTree: state => state.articlesTree,
    getCurrentArticle: state => state.currentArticle,
    getArticleToEdit: state => state.articleToEdit
};

export default {
    state,
    getters,
    actions,
    mutations
};