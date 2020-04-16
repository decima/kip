<template>
    <div>
        <editor v-if="loaded" />
    </div>
</template>

<script>
    import Editor from "pages/article/edit/Editor";

    export default {
        name : "EditArticle",
        components: {Editor},
        data(){
            return {
                loaded : false
            }
        },
        methods : {
            async loadArticleToEditFromPath(){
                await this.$store.dispatch("loadArticleToEditFromPath", this.$readLink());
                this.loaded = true;
            }
        },
        async created(){
            await this.loadArticleToEditFromPath();
        }
    }
</script>

<style scoped>

</style>