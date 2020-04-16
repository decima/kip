<template>
    <div>
        <div>{{ $store.getters.getCurrentArticle.file.webpath }}</div>

        <router-link :to="{ path: $editLink() }">
            <a-button type="link">{{ $t("read.edit") }}</a-button>
        </router-link>

        <a :href="slidesLink"
           target="_blank">
            <a-button type="link">{{ $t("read.slides") }}</a-button>
        </a>

        <delete-article-action />
    </div>
</template>

<script>
    import DeleteArticleAction from "pages/article/read/DeleteArticleAction";

    export default {
        name : "ArticleHeader",
        components: {DeleteArticleAction},
        computed : {
            slidesLink(){
                return Router.url('knowledge_slides', { webpath : this.$store.getters.getCurrentArticle.file.webpath})
            }
        }
    }
</script>

<style scoped>

</style>