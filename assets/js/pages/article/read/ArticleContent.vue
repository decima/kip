<template>
    <div class="article-content">
        <div v-html="$store.getters.getCurrentArticle.file.content"></div>
    </div>
</template>

<script>
    export default {
        name : "ArticleContent"
    }
</script>

<style scoped>

    .article-content {
        padding-right: 20px;
    }

</style>
