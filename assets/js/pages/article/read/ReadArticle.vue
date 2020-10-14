<template>
    <div>
        <div class="read-article"
             v-if="$store.getters.getCurrentArticle"
             :key="$store.getters.getCurrentArticle.file.path"
             v-hotkey="keymap">

            <a-row type="flex">
                <a-col class="article-content-col"
                       :style="{ width : $store.getters.getTocCollapsed ? '100%' : 'calc(100% - 250px)' }">
                    <article-content/>
                </a-col>
                <a-layout-sider v-model="tocCollapsed"
                                :collapsedWidth="0"
                                breakpoint="md"
                                collapsible
                                :trigger="null"
                                @breakpoint="onBreakpointChanged"
                                class="toc-sider">
                    <a-col class="toc-col"
                           v-if="!$store.getters.getTocCollapsed"
                           :style="{ width : '250px', marginLeft : 'auto' }">
                        <table-of-content class="toc"/>
                    </a-col>
                </a-layout-sider>
            </a-row>

            <a-drawer placement="right"
                      class="toc-drawer"
                      :closable="false"
                      :visible="$store.getters.getTocDrawerOpened"
                      @close="onTocDrawerClose"
                      :width="300">
                <table-of-content class="toc"/>
            </a-drawer>
        </div>

        <article-not-found v-if="notFound"/>
    </div>
</template>

<script>
    import TableOfContent from "pages/article/read/TableOfContent";
    import ArticleNotFound from "pages/article/read/ArticleNotFound";
    import ArticleContent from "pages/article/read/ArticleContent";

    import changePageTitleMixin from "mixins/changePageTitleMixin";

    export default {
        name: "ReadArticle",
        components: {ArticleContent, ArticleNotFound, TableOfContent},
        mixins: [changePageTitleMixin],
        data() {
            return {
                notFound: false
            }
        },
        computed: {
            keymap() {
                return {
                    'e': e => this.goToEditArticle(e),
                    'p': e => this.goToPresentationMode(e),
                }
            },
            tocCollapsed: {
                get() {
                    return this.$store.getters.getTocCollapsed
                },
                set(value) {
                    this.$store.commit("setTocCollapsed", value);
                }
            },
        },
        methods: {
            async loadCurrentArticleFromPath() {
                this.notFound = false;
                const currentArticle = await this.$store.dispatch("loadCurrentArticleFromPath", this.$readLink());
                if (!currentArticle.exists) {
                    this.notFound = true
                }
            },
            goToEditArticle(e) {
                if (this.$settings.canEdit && e.target.tagName !== "INPUT") {
                    this.$router.push({path: this.$editLink()});
                }
            },
            goToPresentationMode(e) {
                if (e.target.tagName !== "INPUT") {
                    window.location = Router.url('knowledge_slides', {webpath: this.$getArticleWebpath()})
                }
            },
            onBreakpointChanged(collapsed) {
                this.$store.commit("setTocCollapsed", collapsed);

                if (!collapsed && this.$store.getters.getTocCollapsed) {
                    this.$store.commit("setTocCollapsed", false)
                }
            },
            onTocDrawerClose() {
                this.$store.commit("setTocDrawerOpened", false)
            }
        },
        async created() {
            await this.loadCurrentArticleFromPath();
            this.changePageTitle();
        }
    }
</script>

<style scoped>

    .toc {
        display: flex;
        justify-content: flex-end;
        flex: 0 0 auto;
    }

    .toc-sider {
        background: none;
    }

</style>
