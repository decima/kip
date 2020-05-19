<template>
    <a-tree v-if="$store.getters.getArticlesTree"
            showLine
            :showIcon="true"
            @select="onSelect"
            :treeData="treeData"
            :replaceFields="{
                    children: 'subLinks',
                    title: 'name',
                    key: 'path'
                }"
            :defaultExpandAll="true"
            :selectedKeys="[$getArticleWebpath]"
            class="articles-tree">
        <fa type="fad" icon="folder" slot="switcherIcon"  />
    </a-tree>
</template>

<script>
    export default {
        name: "ArticlesTree",
        computed: {
            treeData() {
                return [this.$store.getters.getArticlesTree.nav];
            }
        },
        methods: {
            onSelect(selectedKeys) {
                //selectedKeys is empty on deselection
                if (selectedKeys.length > 0) {
                    //we don't enable multi selects, so we take the first element of the array
                    const articlePath = `/${selectedKeys[0]}`;
                    if (articlePath !== this.$getArticleWebpath()) {
                        this.$router.push({path: articlePath});
                    }
                }
            }
        }
    }
</script>

<style>

    .articles-tree {
        margin-top: 20px !important;
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
</style>


