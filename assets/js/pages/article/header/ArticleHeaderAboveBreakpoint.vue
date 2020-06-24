<template>
    <div class="article-header--above-breakpoint">
        <div class="left-wrapper">
            <hamburger-button />
            <article-path />
        </div>

        <article-actions />
    </div>
</template>

<script>
    import HamburgerButton from "pages/article/header/HamburgerButton";
    import ArticlePath from "pages/article/header/ArticlePath";
    import ArticleActions from "pages/article/header/ArticleActions";

    export default {
        name : "ArticleHeaderAboveBreakpoint",
        components: {ArticleActions, ArticlePath, HamburgerButton}
    }
</script>

<style scoped>

    .article-header--above-breakpoint {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap:wrap;
        width: 100%;
    }

    .left-wrapper {
        display: flex;
        align-items:center;
    }

</style>
