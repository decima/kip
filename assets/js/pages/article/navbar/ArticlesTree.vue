<template>
    <a-tree v-if="$store.getters.getArticlesTree"
            :showIcon="true"
            :treeData="treeData"
            :replaceFields="{
                    children: 'subLinks',
                    title: 'name',
                    key: 'path'
                }"
            :defaultExpandAll="true"
            :selectedKeys="selectedKeys"
            class="articles-tree"
            :blockNode="true">
        <fa icon="chevron-right" slot="switcherIcon"  />

        <template slot="title" slot-scope="article">
            <article-tree-item :article="article" />
        </template>

        <template slot="icon" slot-scope="article">
            <fa icon="file" v-if="!article.dataRef.isFolder" />
            <fa type="fad" icon="folder" v-if="article.dataRef.isFolder" />
        </template>
    </a-tree>
</template>

<script>
    import ArticleTreeItem from "pages/article/navbar/ArticleTreeItem";
    export default {
        name: "ArticlesTree",
        components: {ArticleTreeItem},
        computed: {
            treeData() {
                const articlesTree = JSON.parse(JSON.stringify(this.$store.getters.getArticlesTree.nav));

                // add a 'Home' link
                articlesTree.subLinks.splice(0,0,{
                    name : this.$t("navBar.home"),
                    path : "",
                    scopedSlots : { title : 'title' }
                });

                // we hide the README at the root of the files to only display the "home" link
                return articlesTree.subLinks.filter(files => files.name.toLowerCase() !== "readme");
            },
            selectedKeys(){
                return [this.$getArticleWebpath()?.substring(1) || this.$route.path.substring(1)]
            }
        },
        watch : {
            "$route.path" : function(){
                if(this.$store.getters.getNavBarDrawerOpened){
                    this.$store.commit("setNavBarDrawerOpened", false)
                }
            }
        }
    }
</script>

<style lang="less">

    .articles-tree {
        margin-top: 10px !important;
    }

    .ant-tree > li:first-child {
        padding-top: 0;
    }

    .ant-tree.ant-tree-show-line li span.ant-tree-switcher {
        background: none !important;
    }

    .ant-tree.ant-tree-show-line li span.ant-tree-switcher.ant-tree-switcher_open .ant-tree-switcher-icon,
    .ant-tree.ant-tree-show-line li span.ant-tree-switcher.ant-tree-switcher_open .ant-select-switcher-icon {
        font-size: 18px !important;
    }

    .ant-tree.ant-tree-block-node li .ant-tree-node-content-wrapper {
        min-width: 100% !important;
        width: auto !important;

        &:not(.ant-tree-node-content-wrapper-normal) {
            min-width: calc(100% - @navbar-padding-horizontal) !important;
        }
    }

    .ant-tree li:not(:last-child)::before {
        position: absolute;
        left: 12px;
        width: 1px;
        height: 100%;
        height: calc(100% - 22px);
        margin: 22px 0 0;
        border-left: 1px solid #d9d9d9;
        content: ' ';
    }

    .ant-tree li {
        position: relative;
    }

    .ant-tree-title {
        display: inline-block;
        width: 100%;
    }

    .ant-tree-iconEle.ant-tree-icon__customize {
        position: relative;
        left: -4px;
    }

    .ant-tree-switcher.ant-tree-switcher-noop {
        display: none !important;
    }

    .ant-tree li ul{
        padding-left: 24px !important;
    }

    .ant-tree-switcher.ant-tree-switcher_open i {
        transform: rotate(90deg) scale(0.8) !important;
    }

</style>


