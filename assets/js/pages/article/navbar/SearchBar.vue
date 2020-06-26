<template>
    <a-select class="search-bar"
              showSearch
              :placeholder="$t('navBar.search')"
              :defaultActiveFirstOption="false"
              :filterOption="false"
              :value="undefined"
              @search="onSearch"
              @change="onChange"
              :notFoundContent="null"
              :dropdownMatchSelectWidth="false"
              ref="searchBar"
              v-hotkey="keymap">

        <a-tooltip class="slash-sign" slot="suffixIcon">
            <template slot="title">{{ $t("navBar.slashTooltip")}}</template>/
        </a-tooltip>

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
        computed : {
            keymap(){
                return {
                    'shift+/' : {
                        keyup : (e) => this.focusSearchBar(e)
                    },
                    '/' : {
                        keyup : (e) =>  this.focusSearchBar(e),
                    }
                }
            }
        },
        methods: {
            async focusSearchBar(e){
                // if we are not currently focusing an input or a textarea, we focus on the search bar
                if(["textarea","input"].indexOf(e.target.type) === -1){
                    this.$refs.searchBar.focus(); // this is not enough as the search input is in 'display:none' by default
                    this.$refs.searchBar.$el.querySelector(".ant-select-search").style.display = "block";
                    this.$refs.searchBar.$el.querySelector("input").focus();
                }
            },
            onSearch(value) {
                const query = value.trim().toLowerCase();
                this.query = query;

                const max = 10;
                if (this.index === null || query.length < 3) {
                    this.results = [];
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

                    if(this.$store.getters.getNavBarDrawerOpened){
                        this.$store.commit("setNavBarDrawerOpened", false)
                    }
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

<style scoped lang="less">

    .search-bar {
        width: 100%;
    }

    .slash-sign {
        border-radius: 4px;
        border:solid 1px @input-placeholder-color;
        padding: 0px 6px;
        position: relative;
        height: 20px;
        line-height: 20px;
        top: -4px;
        font-size: 10px;
    }

</style>
