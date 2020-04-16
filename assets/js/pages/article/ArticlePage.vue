<template>
    <div class="article-page">
        <articles-tree />

        <router-view :key="$route.path" />
    </div>
</template>

<script>
    import ArticlesTree from "pages/article/ArticlesTree";

    export default {
        name : "ArticlePage",
        components: {ArticlesTree}
    }
</script>

<style scoped>

    .article-page {
        display:flex;
    }

</style>