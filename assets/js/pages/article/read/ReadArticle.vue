<template>
    <div>
        <div class="read-article"
             v-if="$store.getters.getCurrentArticle"
             :key="$store.getters.getCurrentArticle.file.path">

            <div>
                <article-header />
                <article-content />
            </div>


            <table-of-content />
        </div>

        <article-not-found v-if="notFound" />
    </div>
</template>

<script>
    import TableOfContent from "pages/article/read/TableOfContent";
    import ArticleNotFound from "pages/article/read/ArticleNotFound";
    import ArticleContent from "pages/article/read/ArticleContent";
    import ArticleHeader from "pages/article/read/ArticleHeader";

    export default {
        name : "ReadArticle",
        components: {ArticleHeader, ArticleContent, ArticleNotFound, TableOfContent},
        data(){
            return {
                notFound : false
            }
        },
        methods : {
            async loadCurrentArticleFromPath(){
                this.notFound = false;
                const currentArticle = await this.$store.dispatch("loadCurrentArticleFromPath", this.$route.path);
                if(!currentArticle){
                    this.notFound = true
                }
            }
        },
        async created(){
            await this.loadCurrentArticleFromPath();
        }
    }
</script>

<style scoped>

    .read-article {
        display:flex;
    }

</style>