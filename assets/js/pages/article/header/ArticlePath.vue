<template>
    <div class="article-path"
         v-if="$store.getters.getCurrentArticle">{{ articleWebPath }}</div>
</template>

<script>

    export default {
        name : "ArticlePath",
        computed: {
            articleWebPath(){
                let articleWebPath = this.$getArticleWebpath();

                if(this.$getArticleWebpath() === "/"){
                    articleWebPath = this.$t("navBar.home")
                }

                if(this.$store.getters.getWindowWidth < 500) {
                    return this.$store.getters.getCurrentArticle.file.name
                }

                let maxLength = this.$store.getters.getWindowWidth < 1000 ? 50 : 70;

                return this.truncate(articleWebPath, maxLength);
            }
        },
        methods : {
            truncate(str, maxLength){
                if (str.length <= maxLength) return str;

                const separator = '...';

                const sepLen = separator.length,
                    charsToShow = maxLength - sepLen,
                    frontChars = Math.ceil(charsToShow/2),
                    backChars = Math.floor(charsToShow/2);

                return str.substr(0, frontChars) +
                    separator +
                    str.substr(str.length - backChars);
            }
        }
    }
</script>

<style scoped>

    .article-path {
        color: #CED4DC;
        font-size: 12px;
        margin-right: 40px;
    }

</style>
