<template>
    <div @click.stop class="article-link-wrapper">
        <router-link class="article-link"
                     :to="{ path : `/${ article.path }` }">{{ article.name }}
        </router-link>
    </div>
</template>

<script>
    export default {
        name: "ArticleTreeItem",
        props: {
            article: {}
        }
    }
</script>

<style scoped lang="less">

    .article-link-wrapper {
        position: relative;
    }

    .article-link {
        display :block;
        color: @text-color;
    }

</style>
