<template>
    <div class="article-page">
        <nav-bar />

        <div class="article-wrapper">
            <article-header />
            <router-view :key="$route.path" />
        </div>
    </div>
</template>

<script>
    import ArticleHeader from "pages/article/header/ArticleHeader";
    import NavBar from "pages/article/navbar/NavBar";

    export default {
        name : "ArticlePage",
        components: {NavBar, ArticleHeader}
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