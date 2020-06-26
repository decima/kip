<template>
    <a-tree v-if="$store.getters.getArticlesTree"
            showLine
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
        <fa type="fad" icon="folder" slot="switcherIcon"  />

        <template slot="title" slot-scope="data">
            <div @click.stop>
                <router-link class="article-link"
                             :to="{ path : `/${ data.path }` }">{{ data.name }}</router-link>
            </div>
        </template>
    </a-tree>
</template>

<script>
    export default {
        name: "ArticlesTree",
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

<style scoped lang="less">

    .article-link {
        display :block;
        color: @text-color;
    }

</style>

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
        min-width: calc(100% - @navbar-padding-horizontal) !important;
        width: auto !important;
    }

</style>


