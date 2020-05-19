<template>
    <a-tree v-if="$store.getters.getArticlesTree"
            showLine
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

</style>


