<template>
    <div>
        <div class="read-article"
             v-if="$store.getters.getCurrentArticle"
             :key="$store.getters.getCurrentArticle.file.path"
             v-hotkey="keymap">

            <a-row type="flex">
                <a-col class="article-content-col"
                       :style="{ width : 'calc(100% - 250px' }">
                    <article-content />
                </a-col>
                <a-col class="toc-col"
                       :style="{ width : '250px', marginLeft : 'auto' }">
                    <table-of-content class="toc" />
                </a-col>
            </a-row>
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
                    'e' : this.goToEditArticle,
                    'p' : this.goToPresentationMode,
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
            },
            goToPresentationMode(){
                window.location = Router.url('knowledge_slides', {webpath: this.$getArticleWebpath()})
            }
        },
        async created(){
            await this.loadCurrentArticleFromPath();
            this.changePageTitle();
        }
    }
</script>

<style scoped>

    .toc {
        display: flex;
        justify-content: flex-end;
        flex : 0 0 auto;
    }

</style>
