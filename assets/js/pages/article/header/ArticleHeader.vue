<template>
    <div class="article-header">
        <article-header-above-breakpoint v-if="$store.getters.getWindowWidth > 768" />
        <article-header-below-breakpoint v-else />
    </div>
</template>

<script>

    import ArticleHeaderAboveBreakpoint from "pages/article/header/ArticleHeaderAboveBreakpoint";
    import ArticleHeaderBelowBreakpoint from "pages/article/header/ArticleHeaderBelowBreakpoint";

    export default {
        name: "ArticleHeader",
        components: {
            ArticleHeaderBelowBreakpoint,
            ArticleHeaderAboveBreakpoint,
        },
    }
</script>

<style scoped lang="less">

    .article-header {
        width: 100%;
    }

</style>
