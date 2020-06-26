<template>
    <div @click.stop class="article-link-wrapper"
         @mouseenter="isMouseOvering = true"
         @mouseleave="isMouseOvering = false">
        <router-link class="article-link"
                     :to="{ path : `/${ article.path }` }">{{ article.name }}
        </router-link>

        <article-tree-item-actions :isMouseOvering="isMouseOvering"
                                   :article="article" />
    </div>
</template>

<script>
    import ArticleTreeItemActions from "pages/article/navbar/ArticleTreeItemActions";
    export default {
        name: "ArticleTreeItem",
        components: {ArticleTreeItemActions},
        props: {
            article: {}
        },
        data(){
            return {
                isMouseOvering : false
            }
        }
    }
</script>

<style scoped lang="less">

    .article-link {
        display :block;
        color: @text-color;
    }

</style>
