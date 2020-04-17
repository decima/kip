<template>
    <div>
        <search-bar />
        <articles-tree />
    </div>
</template>

<script>
    import ArticlesTree from "pages/article/navbar/ArticlesTree";
    import SearchBar from "pages/article/navbar/SearchBar";

    export default {
        name : "NavBar",
        components: {SearchBar, ArticlesTree}
    }
</script>

<style scoped>

</style>