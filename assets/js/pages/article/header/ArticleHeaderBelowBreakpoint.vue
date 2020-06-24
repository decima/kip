<template>
    <div class="article-header--below-breakpoint">
        <div class="line-wrapper">
            <hamburger-button />
            <article-path />
        </div>

        <div class="line-wrapper bottom-actions">
            <article-actions class="article-actions" />
            <a @click="openTocDrawer"
               v-if="$store.getters.getCurrentArticle">{{ $t('read.displayToc') }}</a>
        </div>
    </div>
</template>

<script>
    import HamburgerButton from "pages/article/header/HamburgerButton";
    import ArticlePath from "pages/article/header/ArticlePath";
    import ArticleActions from "pages/article/header/ArticleActions";

    export default {
        name : "ArticleHeaderBelowBreakpoint",
        components: {ArticleActions, ArticlePath, HamburgerButton},
        methods : {
            openTocDrawer(){
                this.$store.commit("setTocDrawerOpened", true)
            }
        }
    }
</script>

<style scoped lang="less">

    .line-wrapper {
        display: flex;
        align-items:center;
    }

    .bottom-actions {
        justify-content: space-between;
        flex-wrap: wrap;
        margin-top : 10px;
    }

    .article-actions {
        margin-right: 20px;
    }

    @media screen and (max-width: 560px){
        .bottom-actions {
            display: block;
        }

        .article-actions {
            margin-bottom: 10px;
            margin-right: 0;
        }
    }

</style>
