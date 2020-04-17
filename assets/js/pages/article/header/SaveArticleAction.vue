<template>
    <a-button type="link" @click="saveArticle" :loading="loading">{{ $t("read.save") }}</a-button>
</template>

<script>
    export default {
        name : "SaveArticleAction",
        data(){
            return {
                loading : false
            }
        },
        methods : {
            async saveArticle(){
                this.loading = true;
                const response = await this.$store.dispatch("saveArticle", {
                    webpath : this.$getArticleWebpath(),
                    content : this.$store.getters.getCurrentArticle.file.markdownContent
                });
                this.loading = false;
                if(response.status === 204){
                    //we deleted the article successfully
                    this.$notification.open({
                        message : this.$t("read.saved"),
                        duration : 2
                    });
                }
            }
        }
    }
</script>

<style scoped>

</style>