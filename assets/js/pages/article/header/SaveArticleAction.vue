<template>
    <a-button type="link" @click="saveArticle" :loading="loading">{{ $t("read.save") }}</a-button>
</template>

<script>

    import saveArticleMixin from "mixins/saveArticleMixin";

    export default {
        name : "SaveArticleAction",
        mixins : [saveArticleMixin],
        data(){
            return {
                loading : false
            }
        }
    }
</script>

<style scoped>

</style>
