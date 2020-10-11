import axios from "axios";

const state = {
    articlesTree: null,
    currentArticle: null,
    canEdit: false,
    canDelete: false,
};

const mutations = {
    setArticlesTree(state, value) {
        state.articlesTree = value;
    },
    setCanEdit(state, value) {
        state.canEdit = value;
    },
    setCanDelete(state, value) {
        state.canDelete = value;
    },
    setCurrentArticle(state, value) {
        state.currentArticle = value;
    },
    setCurrentArticleMarkdownContent(state, value) {
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
        } catch (e) {
            //handle when the file doesn't exist
            if (e.response.status === 404) {
                commit("setCurrentArticle", null);
                return null;
            }
        }
    },
    async loadSettings({commit}) {
        const item = (await axios.get(Router.url("knowledge_settings"))).data;
        console.log(item)
        commit("setCanEdit", item.canEdit);
        commit("setCanDelete", item.canDelete);
        return item;
    },

    async canDelete() {
        const item = (await axios.get(Router.url("knowledge_settings"))).data;
        return item.canDelete;
    },
    async deleteArticle({commit}, webpath) {
        return await axios.get(Router.url("knowledge_delete", {webpath}));
    },
    async loadArticleToEditFromPath({commit}, webpath) {
        const articleToEdit = (await axios.get(Router.url("knowledge_edit", {webpath}))).data;
        commit("setCurrentArticle", articleToEdit);
        return articleToEdit;
    },
    async saveArticle({commit}, payload) {
        return await axios.put(Router.url("knowledge_update", {webpath: payload.webpath}), payload.content);
    },
    async uploadMedia({commit}, payload) {
        return await axios.post(Router.url("knowledge_upload", {webpath: payload.filepath}), payload.binaryContent, {
            params: {
                media: payload.media ? 1 : '',
                name: payload.filename,
                parentFolder: payload.parentFolder
            }
        });
    }
};

const getters = {
    getArticlesTree: state => state.articlesTree,
    getCurrentArticle: state => state.currentArticle,
    getCanEdit: state => state.canEdit,
    getCanDelete: state => state.canDelete
};

export default {
    state,
    getters,
    actions,
    mutations
};
