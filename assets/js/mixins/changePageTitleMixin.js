export default {
    methods: {
        changePageTitle() {
            const titleFromMetadata = this.$store.getters.getCurrentArticle?.file?.metadata?.title;
            const articleTitle = this.$store.getters.getCurrentArticle?.file?.title;
            const fileName = this.$store.getters.getCurrentArticle?.file?.name;
            const title = titleFromMetadata || articleTitle || fileName;
            document.title = `${title || this.$t('read.notFoundTitle')} | KIP`
        }
    }
}
