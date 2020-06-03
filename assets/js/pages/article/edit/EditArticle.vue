<template>
    <div class="edit-article">
        <editor ref="editor" v-if="loaded"/>
    </div>
</template>

<script>

    import Editor from "pages/article/edit/Editor";
    import saveArticleMixin from "mixins/saveArticleMixin";

    export default {
        name: "EditArticle",
        components: {Editor},
        mixins : [saveArticleMixin],
        data() {
            return {
                loaded: false
            }
        },
        methods: {
            async loadArticleToEditFromPath() {
                await this.$store.dispatch("loadArticleToEditFromPath", this.$readLink());
                this.loaded = true;
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
        }
    }
</script>

<style scoped>

    .edit-article {
        width: 100%;
        height: 100%;
    }

</style>
