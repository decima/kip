<template>
    <div v-if="$store.getters.getArticlesTree">
        <a-tree showLine
                @select="onSelect"
                :treeData="treeData"
                :replaceFields="{
                    children: 'subLinks',
                    title: 'name',
                    key: 'path'
                }"
                :defaultExpandAll="true"
                :selectedKeys="[$getArticleWebpath]">
        </a-tree>
    </div>
</template>

<script>
    export default {
        name: "ArticlesTree",
        computed : {
            treeData(){
                return [this.$store.getters.getArticlesTree.nav];
            }
        },
        methods: {
            onSelect(selectedKeys) {
                //selectedKeys is empty on deselection
                if(selectedKeys.length > 0){
                    //we don't enable multi selects, so we take the first element of the array
                    const articlePath = `/${selectedKeys[0]}`;
                    if(articlePath !== this.$getArticleWebpath()) {
                        this.$router.push({ path : articlePath });
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>