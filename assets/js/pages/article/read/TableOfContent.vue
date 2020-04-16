<template>
    <div>
        <a-tree showLine
                @select="onSelect"
                :treeData="$store.getters.getCurrentArticle.file.tree"
                :replaceFields="{
                    children: 'children',
                    title: 'text',
                    key: 'id'
                }"
                :defaultExpandAll="true">
        </a-tree>
    </div>
</template>

<script>
    export default {
        name : "TableOfContent",
        methods : {
            onSelect(){

            }
        }
    }
</script>

<style scoped>

</style>