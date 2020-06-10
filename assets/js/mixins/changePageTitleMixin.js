export default {
    methods : {
        changePageTitle(){
            const titleFromMetadata = this.$store.getters.getCurrentArticle.file.metadata?.title;
            const articleTitle = this.$store.getters.getCurrentArticle.file.title;
            document.title = `${ titleFromMetadata || articleTitle } | KIP`
        }
    }
}
