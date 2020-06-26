<template>
    <div class="article-content language-none"
         v-html="$store.getters.getCurrentArticle.file.content"
         :style="{ paddingRight : $store.getters.getTocCollapsed ? '0' : '40px' }"></div>
</template>

<script>

    import Prism from 'prismjs';
    import 'prismjs/plugins/autoloader/prism-autoloader.min';
    import 'prismjs/themes/prism.css';

    export default {
        name : "ArticleContent",
        async mounted(){
           Prism.highlightAll();
        }
    }
</script>

<style scoped lang="less">

    .article-content {
        padding-right: 40px;
        width: 100%;
    }

</style>
