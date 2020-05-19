<template>
    <a-select class="search-bar"
              showSearch
              :defaultActiveFirstOption="false"
              :showArrow="false"
              :filterOption="false"
              :value="null"
              @search="onSearch"
              @change="onChange"
              :notFoundContent="null"
              :dropdownMatchSelectWidth="false">
        <a-select-option v-for="result in results" :key="result.webpath">
            <result-item :result="result" :query="query"/>
        </a-select-option>
    </a-select>

</template>

<script>

    import Flexsearch from "flexsearch";
    import ResultItem from "pages/article/navbar/ResultItem";

    export default {
        name: "SearchBar",
        components: {ResultItem},
        data() {
            return {
                query: "",
                results: []
            }
        },
        methods: {
            onSearch(value) {
                const query = value.trim().toLowerCase();
                this.query = query;

                const max = 10;
                if (this.index === null || query.length < 3) {
                    return;
                }
                // here we use the search method that FlexSearch provides.
                this.index.search(
                    query,
                    {
                        limit: max,
                        threshold: 2,
                        encode: 'extra'
                    },
                    (results) => {
                        if (results.length) {
                            this.results = results;
                        } else {
                            //@TODO display a no result text
                            this.results = []
                        }
                    }
                );
            },
            onChange(value) {
                const articlePath = `/${value}`;
                if (articlePath !== this.$getArticleWebpath()) {
                    this.$router.push({path: articlePath});
                }
            }
        },
        mounted() {
            this.index = new Flexsearch({
                tokenize: "forward",
                doc: {
                    id: "webpath",
                    field: ["title", "content"]
                }
            });
            this.index.add(this.$store.getters.getArticlesTree.indexedArticles);
        }
    }
</script>

<style scoped>

    .search-bar {
        width: 100%;
    }

</style>
