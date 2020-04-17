<template>
    <div v-if="$store.getters.getCurrentArticle">
        <div>{{ $getArticleWebpath() }}</div>

        <router-link :to="{ path: $readLink() }">
            <a-button type="link"><fa icon="eye" /></a-button>
        </router-link>

        <router-link :to="{ path: $editLink() }">
            <a-button type="link"><fa icon="pencil" /></a-button>
        </router-link>

        <a :href="slidesLink"
           target="_blank">
            <a-button type="link"><fa icon="presentation" /></a-button>
        </a>

        <delete-article-action />

        <save-article-action v-if="$route.name === $routes.EDIT_ARTICLE" />
    </div>
</template>

<script>
    import DeleteArticleAction from "./DeleteArticleAction";
    import SaveArticleAction from "pages/article/header/SaveArticleAction";

    export default {
        name : "ArticleHeader",
        components: {SaveArticleAction, DeleteArticleAction},
        computed : {
            slidesLink(){
                return Router.url('knowledge_slides', { webpath : this.$getArticleWebpath() })
            }
        }
    }
</script>

<style scoped>

</style>