<template>
    <div>
        <div class="read-article"
             v-if="$store.getters.getCurrentArticle"
             :key="$store.getters.getCurrentArticle.file.path">

            <!--<a-row :gutter="40" :style="{ width : '100%' }">
                <a-col :md="{ span : 19 }" :xs="{ span : 24 }"><article-content /></a-col>
                <a-col :md="{ span : 5 }" :xs="{ span : 0 }"><table-of-content class="toc" /></a-col>
            </a-row>-->
            <div class="read-article-content">
                <article-content />
                <div class="toc-wrapper">
                    <table-of-content class="toc" />
                </div>
            </div>
        </div>

        <article-not-found v-if="notFound" />
    </div>
</template>

<script>
    import TableOfContent from "pages/article/read/TableOfContent";
    import ArticleNotFound from "pages/article/read/ArticleNotFound";
    import ArticleContent from "pages/article/read/ArticleContent";

    export default {
        name : "ReadArticle",
        components: {ArticleContent, ArticleNotFound, TableOfContent},
        data(){
            return {
                notFound : false
            }
        },
        methods : {
            async loadCurrentArticleFromPath(){
                this.notFound = false;
                const currentArticle = await this.$store.dispatch("loadCurrentArticleFromPath", this.$readLink());
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

    .toc {
        display: flex;
        justify-content: flex-end;
    }

    .read-article-content {
        width: 100%;
        display: flex;
        justify-content: space-between;
    }

</style>
