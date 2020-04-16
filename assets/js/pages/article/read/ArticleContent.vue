<template>
    <div>
        <div v-html="$store.getters.getCurrentArticle.file.content"></div>
    </div>
</template>

<script>
    export default {
        name : "ArticleContent"
    }
</script>

<style scoped>

</style>