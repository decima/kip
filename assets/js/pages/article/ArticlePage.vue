<template>
    <div class="article-page">
        <articles-tree />

        <div class="article-wrapper">
            <article-header />
            <router-view :key="$route.path" />
        </div>
    </div>
</template>

<script>
    import ArticlesTree from "pages/article/ArticlesTree";
    import ArticleHeader from "pages/article/header/ArticleHeader";

    export default {
        name : "ArticlePage",
        components: {ArticleHeader, ArticlesTree}
    }
</script>

<style scoped>

    .article-page {
        display:flex;
    }

    .article-wrapper {
        width : 100%;
    }

</style>