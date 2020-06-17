<template>
    <div class="edit-article">
        <editor ref="editor" v-if="loaded"/>
    </div>
</template>

<script>

    import Editor from "pages/article/edit/Editor";
    import saveArticleMixin from "mixins/saveArticleMixin";

    import changePageTitleMixin from "mixins/changePageTitleMixin";

    export default {
        name: "EditArticle",
        components: {Editor},
        mixins : [saveArticleMixin, changePageTitleMixin],
        data() {
            return {
                loaded: false
            }
        },
        methods: {
            async loadArticleToEditFromPath() {
                await this.$store.dispatch("loadArticleToEditFromPath", this.$readLink());
                this.loaded = true;
            },
            initPreventCloseTabIfChangesNotSaved(){
                window.onbeforeunload = () => {
                    if(this.$refs.editor.areThereAnyChangesNotSaved()){
                        return this.$t("edit.changesNotSavedDescription")
                    }
                };
            }
        },
        beforeRouteLeave(to, from, next){
            if(this.$refs.editor.areThereAnyChangesNotSaved()){
                this.$confirm({
                    title: this.$t("edit.changesNotSaved"),
                    content: this.$t("edit.changesNotSavedDescription"),
                    onOk : async () => {
                        await this.saveArticle();
                        next();
                    },
                    onCancel() {
                        next(false);
                    },
                });
            } else {
                next()
            }
        },
        async created() {
            await this.loadArticleToEditFromPath();
            this.changePageTitle();
            this.initPreventCloseTabIfChangesNotSaved();
        }
    }
</script>

<style scoped>

    .edit-article {
        width: 100%;
        height: calc(100% - 50px);
    }

</style>
