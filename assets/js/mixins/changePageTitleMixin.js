export default {
    methods : {
        changePageTitle(){
            const titleFromMetadata = this.$store.getters.getCurrentArticle?.file?.metadata?.title;
            const articleTitle = this.$store.getters.getCurrentArticle?.file?.title;
            const title = titleFromMetadata || articleTitle;
            document.title = `${ title || this.$t('read.notFoundTitle') } | KIP`
        }
    }
}
