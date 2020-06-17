<template>
    <div>
        <div class="read-article"
             v-if="$store.getters.getCurrentArticle"
             :key="$store.getters.getCurrentArticle.file.path"
             v-hotkey="keymap">

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

    import changePageTitleMixin from "mixins/changePageTitleMixin";

    export default {
        name : "ReadArticle",
        components: {ArticleContent, ArticleNotFound, TableOfContent},
        mixins : [changePageTitleMixin],
        data(){
            return {
                notFound : false
            }
        },
        computed : {
            keymap(){
                return {
                    'e' : this.goToEditArticle
                }
            }
        },
        methods : {
            async loadCurrentArticleFromPath(){
                this.notFound = false;
                const currentArticle = await this.$store.dispatch("loadCurrentArticleFromPath", this.$readLink());
                if(!currentArticle){
                    this.notFound = true
                }
            },
            goToEditArticle(){
                this.$router.push({ path: this.$editLink() });
            }
        },
        async created(){
            await this.loadCurrentArticleFromPath();
            this.changePageTitle();
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
