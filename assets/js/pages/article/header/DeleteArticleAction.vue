<template>
    <a-button type="link" @click="deleteArticle" :loading="loading">{{ $t("read.delete") }}</a-button>
</template>

<script>
    export default {
        name : "DeleteArticleAction",
        data(){
            return {
                loading : false
            }
        },
        methods : {
            async deleteArticle(){
               this.loading = true;
                const response = await this.$store.dispatch("deleteArticle", this.$getArticleWebpath());
                this.loading = false;
                if(response.status === 204){
                    //we deleted the article successfully
                    this.$notification.open({
                        message : this.$t("read.deleted"),
                        duration : 2
                    });

                    //we reload the articles tree (menu) to have it without the deleted file
                    this.$store.dispatch("loadArticlesTree");

                    //we wait a little bit to switch the page not to be too blunt
                    setTimeout(() => {
                        this.$router.push({ path : '/' }); //go back home
                    }, 1000);
                }
            }
        }
    }
</script>

<style scoped>

</style>