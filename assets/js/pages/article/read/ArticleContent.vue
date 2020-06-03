<template>
    <div class="article-content">
        <viewer :initialValue="$store.getters.getCurrentArticle.file.markdownContent" />
    </div>
</template>

<script>

    import '@toast-ui/editor/dist/toastui-editor-viewer.css';

    import { Viewer } from '@toast-ui/vue-editor';

    export default {
        name : "ArticleContent",
        components : { Viewer }
    }
</script>

<style scoped>

    .article-content {
        padding-right: 20px;
        width: 100%;
    }

</style>
