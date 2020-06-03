export default {
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
