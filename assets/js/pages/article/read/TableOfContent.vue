<template>
    <a-anchor :offsetTop="50">
        <anchor-links :article-tree="$store.getters.getCurrentArticle.file.tree"/>
    </a-anchor>
</template>

<script>
    import AnchorLinks from "pages/article/read/AnchorLinks";

    export default {
        name: "TableOfContent",
        components: {AnchorLinks},
    }
</script>

<style>

    .ant-anchor-wrapper, .toc-wrapper {
        width: 250px;
    }

</style>
